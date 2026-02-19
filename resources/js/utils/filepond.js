import * as FilePond from "filepond";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImageEdit from "filepond-plugin-image-edit";
import FilePondPluginImageTransform from "filepond-plugin-image-transform";
import FilePondPluginImageResize from "filepond-plugin-image-resize";
import FilePondPluginImageCrop from "filepond-plugin-image-crop";

import { openDefaultEditor } from "@pqina/pintura";

import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";
import "@pqina/pintura/pintura.css";

FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    // FilePondPluginImageEdit,
    // FilePondPluginImageTransform,
    // FilePondPluginImageResize,
    // FilePondPluginImageCrop,
);

const FilePondHelper = (function () {
    function getCsrfToken() {
        return document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
    }

    /**
     * Init FilePond
     *
     * @param {Object} options
     * @param {String} options.selector
     * @param {String} options.uploadUrl
     * @param {String} options.deleteUrl
     * @param {String|null} options.existingFileUrl
     * @param {Array} options.acceptedFileTypes
     * @param {Boolean} options.multiple
     */

    function init({
        selector,
        uploadUrl,
        deleteUrl,
        existingFileUrl = null,
        acceptedFileTypes = [],
        allowedMimeTypes = [],
        multiple = false,
        maxFileSize = null,
        maxSize = 5120,
        folder = "tmp",
    }) {
        const input = document.querySelector(selector);
        if (!input) return null;

        const pond = FilePond.create(input, {
            allowMultiple: multiple,
            allowReplace: !multiple,
            acceptedFileTypes,
            maxFileSize,

            server: {
                process: (
                    fieldName,
                    file,
                    metadata,
                    load,
                    error,
                    progress,
                    abort,
                ) => {
                    const formData = new FormData();
                    formData.append("file", file);
                    formData.append("max_size", maxSize);
                    formData.append("folder", folder);

                    if (allowedMimeTypes.length) {
                        allowedMimeTypes.forEach((type) =>
                            formData.append("allowed_types[]", type),
                        );
                    }

                    const request = new XMLHttpRequest();
                    request.open("POST", uploadUrl);
                    request.setRequestHeader("X-CSRF-TOKEN", getCsrfToken());

                    request.upload.onprogress = (e) =>
                        progress(e.lengthComputable, e.loaded, e.total);

                    request.onload = function () {
                        let response = {};
                        try {
                            response = JSON.parse(request.responseText);
                        } catch (e) {
                            Toast.error("Server error");
                            return;
                        }

                        if (request.status >= 200 && request.status < 300) {
                            load(response.id);
                        } else {
                            const message =
                                response?.errors?.file?.[0] ||
                                response?.message ||
                                "Upload failed";
                            Toast.error("Upload failed", message);
                            error(message);
                        }
                    };

                    request.send(formData);

                    return {
                        abort: () => {
                            request.abort();
                            abort();
                        },
                    };
                },

                revert: (uniqueFileId, load, error) => {
                    const request = new XMLHttpRequest();
                    request.open("DELETE", deleteUrl);
                    request.setRequestHeader("X-CSRF-TOKEN", getCsrfToken());
                    request.onload = function () {
                        if (request.status >= 200 && request.status < 300)
                            load();
                        else error("Delete failed");
                    };
                    request.send(uniqueFileId);
                },
            },
        });

        if (existingFileUrl) {
            pond.files = [
                { source: existingFileUrl, options: { type: "local" } },
            ];
        }

        return pond;
    }

    return { init };
})();

export default FilePondHelper;
