import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";

const PageScript = (function () {
    let datatable;

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const DataTable = () => {
        datatable = initDatatable({
            table: "#users-datatable",
            ajax: {
                url: route("users.index"),
                method: "GET",
                dataSrc: "data",
            },
            columns: [
                {
                    data: "name",
                    width: "25%",
                },
                {
                    data: "email",
                    width: "25%",
                },
                {
                    data: "roles",
                    width: "25%",
                    className: "text-center",
                    render(data) {
                        if (!Array.isArray(data)) return "";

                        return `
                            <div class="inline-flex flex-wrap gap-2">
                                ${data
                                    .map((role) => renderRoleBadge(role.name))
                                    .join("")}
                            </div>
                        `;
                    },
                },
                {
                    data: "is_active",
                    width: "20%",
                    orderable: false,
                    searchable: false,
                    className: "px-4 py-3 text-center",
                    render(data) {
                        const isActive = data === true;

                        return `
                            <div class="flex justify-center items-center w-full">
                                ${
                                    isActive
                                        ? `<i data-lucide="check-circle" class="size-4 text-green-500"></i>`
                                        : `<i data-lucide="x-circle" class="size-4 text-red-500"></i>`
                                }
                            </div>
                        `;
                    },
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
                                <a href="${route("users.edit", id)}"
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
                                </div>
                            </div>
                        </div>
                        `;
                    },
                },
            ],
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
            await ApiProvider.delete(route("users.destroy", userId));
            Toast.success("Success", "User deleted successfully");

            reloadDatatable();
        } catch (error) {
            console.error("Delete user error:", error);
        }
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", function (e) {
            e.preventDefault();
            const userId = $(this).data("user-id");
            handleDelete(userId);
        });
    };

    function renderRoleBadge(roleName) {
        const baseClass =
            "inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium";
        const map = {
            "Super Admin": `${baseClass} bg-(--color-badge-danger)/50 dark:bg-(--color-badge-danger-dark)/50 text-(--color-badge-danger-foreground) dark:text-(--color-badge-danger)`,
            Admin: `${baseClass} bg-(--color-badge-warning)/50 dark:bg-(--color-badge-warning-dark)/50 text-(--color-badge-warning-foreground) dark:text-(--color-badge-warning)`,
        };
        const classes =
            map[roleName] ?? `${baseClass} bg-muted text-muted-foreground-1`;

        return `<span class="${classes}">${roleName}</span>`;
    }

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
