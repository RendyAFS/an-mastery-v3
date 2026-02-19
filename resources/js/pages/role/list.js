import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";

const PageScript = (function () {
    let datatable;

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const DataTable = () => {
        datatable = initDatatable({
            table: "#roles-datatable",
            ajax: {
                url: route("roles.index"),
                method: "GET",
                dataSrc: "data",
            },
            columns: [
                {
                    data: "name",
                },
                {
                    data: "id",
                    width: "5%",
                    orderable: false,
                    searchable: false,
                    className: "px-4 py-3 text-center",
                    render(id) {
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
                                <a href="${route("roles.edit", id)}"
                                    class="flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                    text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-gray)/20
                                    focus:outline-hidden focus:bg-dropdown-item-focus">
                                    <i data-lucide="square-pen" class="size-4"></i>
                                    Edit
                                </a>
                                <button type="button" data-role-id="${id}"
                                    class="btn-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                    text-(--color-red) hover:bg-(--color-gray)/20
                                    focus:outline-hidden focus:bg-dropdown-item-focus">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                    Delete
                                </button>
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
            const roleId = $(this).data("role-id");
            handleDelete(roleId);
        });
    };

    const handleDelete = async (roleId) => {
        const confirmed = await Confirm.delete(
            "Are you sure you want to delete this role? This action cannot be undone.",
        );

        if (!confirmed) {
            return;
        }

        try {
            await ApiProvider.delete(route("roles.destroy", roleId));
            Toast.success("Success", "User deleted successfully");

            reloadDatatable();
        } catch (error) {
            console.error("Delete role error:", error);
        }
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
