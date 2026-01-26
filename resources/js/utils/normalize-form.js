/**
 * @param {HTMLFormElement} form
 * @param {Object} data
 * @returns {Object}
 */
function normalizeFormInputs(form, data) {
    $(form)
        .find('input[type="checkbox"][name]')
        .each(function () {
            const name = this.name;
            const checked = this.checked;

            const match = name.match(/^(\w+)\[(\w+)\]$/);

            if (match) {
                const parent = match[1];
                const key = match[2];

                if (!data[parent] || typeof data[parent] !== "object") {
                    data[parent] = {};
                }

                data[parent][key] = checked ? 1 : 0;
            } else {
                data[name] = checked ? 1 : 0;
            }
        });

    $(form)
        .find("select.select2[name]")
        .each(function () {
            const name = this.name;
            const val = $(this).val();

            data[name] = val === "" || val === null ? null : val;
        });

    return data;
}

export default normalizeFormInputs;
