document.addEventListener("DOMContentLoaded", function () {
    const backAnchor = document.getElementById("backAnchor");
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            e.preventDefault();
            backAnchor.click();
            return;
        }
    });
});