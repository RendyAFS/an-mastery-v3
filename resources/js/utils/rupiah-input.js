const RupiahInput = {
    selector: "[data-rupiah]",

    init() {
        document.querySelectorAll(this.selector).forEach((input) => {
            // format awal kalau sudah ada value
            if (input.value) {
                input.value = this.format(input.value);
            }

            input.addEventListener("input", (e) => {
                const raw = this.unformat(e.target.value);
                e.target.value = this.format(raw);
            });

            // sebelum submit, ubah ke angka asli
            input.form?.addEventListener("submit", () => {
                input.value = this.unformat(input.value);
            });
        });
    },

    format(value) {
        if (!value) return "";

        const number = value.toString().replace(/\D/g, "");
        if (!number) return "";

        return new Intl.NumberFormat("id-ID").format(number);
    },

    unformat(value) {
        return value.toString().replace(/\D/g, "");
    },
};

document.addEventListener("DOMContentLoaded", () => {
    RupiahInput.init();
});

export default RupiahInput;
