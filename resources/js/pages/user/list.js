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
                    render(data, type, row) {
                        const checked = data ? "checked" : "";

                        return `
                            <div class="flex items-center justify-center gap-x-3">
                                <label for="toggle-active-${row.id}" class="relative inline-block w-11 h-6 cursor-pointer">
                                    <input type="checkbox" id="toggle-active-${row.id}" class="peer sr-only toggle-active" data-user-id="${row.id}" ${checked}>
                                    <span class="absolute inset-0 bg-(--color-dark-gray) rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-(--color-success) peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                                    <span class="absolute top-1/2 start-0.5 -translate-y-1/2 size-5 bg-(--color-light) rounded-full shadow-sm transition-transform duration-200 ease-in-out peer-checked:translate-x-full"></span>
                                </label>
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

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", function (e) {
            e.preventDefault();
            const userId = $(this).data("user-id");
            handleDelete(userId);
        });

        $(document).on("change", ".toggle-active", function () {
            const userId = $(this).data("user-id");
            handleToggleActive(userId, this);
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

    const handleToggleActive = async (userId, checkbox) => {
        const confirmed = await Confirm.show(
            "Are you sure you want to change this user status?",
            "Confirmation",
            "Yes",
            "Cancel",
        );

        if (!confirmed) {
            checkbox.checked = !checkbox.checked;
            return;
        }

        try {
            await ApiProvider.put(route("users.toggle-active", userId));

            Toast.success("Success", "User status updated");
            reloadDatatable();
        } catch (error) {
            checkbox.checked = !checkbox.checked;
            Toast.error("Error", "Failed to update user status");
            console.error(error);
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
