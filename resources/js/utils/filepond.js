import * as FilePond from "filepond";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";

import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";

FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
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
        acceptedFileTypes = ["image/*"],
        multiple = false,
    }) {
        const input = document.querySelector(selector);
        if (!input) return null;

        const pond = FilePond.create(input, {
            allowMultiple: multiple,
            allowReplace: !multiple,
            acceptedFileTypes,

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

                    const request = new XMLHttpRequest();
                    request.open("POST", uploadUrl);
                    request.setRequestHeader("X-CSRF-TOKEN", getCsrfToken());

                    request.upload.onprogress = (e) => {
                        progress(e.lengthComputable, e.loaded, e.total);
                    };

                    request.onload = function () {
                        if (request.status >= 200 && request.status < 300) {
                            const response = JSON.parse(request.responseText);
                            load(response.id); // 🔥 kirim tmp path
                        } else {
                            error("Upload failed");
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
                        if (request.status >= 200 && request.status < 300) {
                            load();
                        } else {
                            error("Delete failed");
                        }
                    };

                    request.send(uniqueFileId);
                },
            },
        });

        // Load existing file (edit mode)
        if (existingFileUrl) {
            pond.files = [
                {
                    source: existingFileUrl,
                    options: {
                        type: "local",
                    },
                },
            ];
        }

        return pond;
    }

    return {
        init,
    };
})();

export default FilePondHelper;
