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

    function init({
        selector,
        uploadUrl,
        deleteUrl,
        loadUrl = null,
        existingFileUrl = null,
        existingFilePath = null,
        acceptedFileTypes = [],
        allowedMimeTypes = [],
        multiple = false,
        maxFileSize = null,
        maxSize = 5120,
        folder = "tmp",
        isCircle = false,
    }) {
        const input = document.querySelector(selector);
        if (!input) return null;

        const pond = FilePond.create(input, {
            allowMultiple: multiple,
            allowReplace: !multiple,
            acceptedFileTypes,
            maxFileSize,

            ...(isCircle && {
                imageCropAspectRatio: "1:1",
                stylePanelLayout: "compact circle",
                imagePreviewHeight: 150,
                styleLoadIndicatorPosition: "center bottom",
                styleProgressIndicatorPosition: "right bottom",
                styleButtonRemoveItemPosition: "left bottom",
                styleButtonProcessItemPosition: "right bottom",
            }),

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
                            Toast.error(
                                window.langFilepond?.server_error ??
                                    "Server error",
                            );
                            return;
                        }

                        if (request.status >= 200 && request.status < 300) {
                            load(response.id);
                        } else {
                            const message =
                                response?.errors?.file?.[0] ||
                                response?.message ||
                                (window.langFilepond?.upload_failed ??
                                    "Upload failed");
                            Toast.error(
                                window.langFilepond?.upload_failed ??
                                    "Upload failed",
                                message,
                            );
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

                load: loadUrl
                    ? (source, load, error, progress, abort, headers) => {
                          const request = new XMLHttpRequest();
                          request.open(
                              "GET",
                              `${loadUrl}?file=${encodeURIComponent(source)}`,
                          );
                          request.responseType = "blob";

                          request.onload = function () {
                              if (
                                  request.status >= 200 &&
                                  request.status < 300
                              ) {
                                  load(request.response);
                              } else {
                                  error(
                                      window.langFilepond?.load_error ??
                                          "Error loading file",
                                  );
                              }
                          };

                          request.onerror = () =>
                              error(
                                  window.langFilepond?.network_error ??
                                      "Network error",
                              );
                          request.send();

                          return {
                              abort: () => {
                                  request.abort();
                                  abort();
                              },
                          };
                      }
                    : undefined,

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

        if (existingFilePath && loadUrl) {
            pond.files = [
                { source: existingFilePath, options: { type: "local" } },
            ];
        } else if (existingFileUrl) {
            pond.files = [
                { source: existingFileUrl, options: { type: "local" } },
            ];
        }

        return pond;
    }

    return { init };
})();

export default FilePondHelper;
