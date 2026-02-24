import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";

const PageScript = (function () {
    let datatable;

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const DataTable = () => {
        datatable = initDatatable({
            table: "#suppliers-datatable",
            filterSelector: "#filter-suppliers",
            ajax: {
                url: route("suppliers.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-suppliers").val();
                },
            },
            columns: [
                {
                    data: "name",
                    width: "35%",
                },
                {
                    data: "address",
                    width: "35%",
                    className: "text-center",
                },
                {
                    data: "contact",
                    width: "25%",
                    className: "text-center",
                    render: (data) => {
                        return `<span class="block text-center">${data}</span>`;
                    },
                },
                {
                    data: "id",
                    width: "5%",
                    orderable: false,
                    searchable: false,
                    className: "px-4 py-3 text-center",
                    render(id, type, row) {
                        const isDeleted = row.deleted_at !== null;

                        return `
                        <div class="hs-dropdown [--auto-close:inside] relative inline-flex">
                            <button type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-3 px-3 py-2
                                text-sm font-medium rounded-lg
                                bg-(--color-gray)/20 dark:bg-(--color-dark-gray)/20
                                hover:bg-(--color-gray)/40
                                cursor-pointer">
                                <i data-lucide="ellipsis-vertical" class="size-4"></i>
                            </button>

                            <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 mt-2 hidden z-10
                                transition-[margin,opacity] opacity-0 duration-300
                                w-auto bg-(--color-light) dark:bg-(--color-dark) dark:border dark:border-(--color-gray)/30
                                shadow-md rounded-lg p-2"
                                role="menu" aria-orientation="vertical">

                                <div class="p-1 space-y-0.5">

                                ${
                                    !isDeleted
                                        ? `
                                        <a href="${route("suppliers.edit", id)}"
                                            class="flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus">
                                            <i data-lucide="square-pen" class="size-4"></i>
                                            Edit
                                        </a>

                                        <button type="button" data-user-id="${id}"
                                            class="btn-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-red) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus">
                                            <i data-lucide="trash-2" class="size-4"></i>
                                            Delete
                                        </button>
                                        `
                                        : `
                                        <button type="button" data-user-id="${id}"
                                            class="btn-restore w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-success) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus">
                                            <i data-lucide="rotate-ccw" class="size-4"></i>
                                            Restore
                                        </button>

                                        <button type="button" data-user-id="${id}"
                                            class="btn-force-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-red) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus">
                                            <i data-lucide="trash" class="size-4"></i>
                                            Force Delete
                                        </button>
                                        `
                                }
                                </div>
                            </div>
                        </div>
                        `;
                    },
                },
            ],
        });
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", function (e) {
            e.preventDefault();
            const userId = $(this).data("user-id");
            handleDelete(userId);
        });
    };

    const handleDelete = async (userId) => {
        const confirmed = await Confirm.delete(
            "Are you sure you want to delete this user? This action cannot be undone.",
        );

        if (!confirmed) {
            return;
        }

        try {
            await ApiProvider.delete(route("suppliers.destroy", userId));
            Toast.success("Success", "Supplier deleted successfully");

            reloadDatatable();
        } catch (error) {
            console.error("Delete user error:", error);
        }
    };

    $(document).on("click", ".btn-restore", function () {
        const id = $(this).data("user-id");
        handleRestore(id);
    });

    const handleRestore = async (id) => {
        const confirmed = await Confirm.show(
            "Restore this user?",
            "Confirmation",
        );

        if (!confirmed) return;

        await ApiProvider.put(route("suppliers.restore", id));

        Toast.success("Success", "Supplier restored");
        reloadDatatable();
    };

    $(document).on("click", ".btn-force-delete", function () {
        const id = $(this).data("user-id");
        handleForceDelete(id);
    });

    const handleForceDelete = async (id) => {
        const confirmed = await Confirm.delete(
            "This will permanently delete the user. Continue?",
        );

        if (!confirmed) return;

        await ApiProvider.delete(route("suppliers.force-delete", id));

        Toast.success("Success", "Supplier permanently deleted");
        reloadDatatable();
    };

    return {
        init() {
            DataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
