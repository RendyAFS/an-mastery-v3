import Alpine from "alpinejs";
import sablonForm from "./alpine-component";
import PageScript from "./page-script";

document.addEventListener("alpine:init", () => {
    Alpine.data("sablonForm", sablonForm);
});

$(function () {
    PageScript.init();
});
