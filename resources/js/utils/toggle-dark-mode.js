const html = document.querySelector("html");
const isLightOrAuto =
    localStorage.getItem("hs_theme") === "light" ||
    (localStorage.getItem("hs_theme") === "auto" &&
        !window.matchMedia("(prefers-color-scheme: dark)").matches);
const isDarkOrAuto =
    localStorage.getItem("hs_theme") === "dark" ||
    (localStorage.getItem("hs_theme") === "auto" &&
        window.matchMedia("(prefers-color-scheme: dark)").matches);

if (isLightOrAuto && html.classList.contains("dark"))
    html.classList.remove("dark");
else if (isDarkOrAuto && html.classList.contains("light"))
    html.classList.remove("light");
else if (isDarkOrAuto && !html.classList.contains("dark"))
    html.classList.add("dark");
else if (isLightOrAuto && !html.classList.contains("light"))
    html.classList.add("light");

function syncThemeIcon() {
    const theme = localStorage.getItem("hs_theme") || "auto";

    document
        .querySelectorAll(".theme-icon")
        .forEach((i) => i.classList.add("hidden"));

    if (theme === "light") {
        document.querySelector(".theme-light")?.classList.remove("hidden");
    } else if (theme === "dark") {
        document.querySelector(".theme-dark")?.classList.remove("hidden");
    } else {
        document.querySelector(".theme-auto")?.classList.remove("hidden");
    }
}

syncThemeIcon();

document.addEventListener("click", (e) => {
    if (e.target.closest("[data-hs-theme-click-value]")) {
        setTimeout(syncThemeIcon, 50);
    }
});
