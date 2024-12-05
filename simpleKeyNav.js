document.addEventListener("DOMContentLoaded", () => {
    const inputs = document.querySelectorAll("input[type='text']");
    let index = 0;
    document.addEventListener("keydown", (e) => {
        switch (e.key) {
            case "ArrowDown":
                index++;
                break;
            case "ArrowUp":
                index--;
                break;
        }
        if (index < 0) {
            index = inputs.length - 1;
        } else if (index > inputs.length - 1) {
            index = 0;
        }
        inputs[index].focus();
    });
});