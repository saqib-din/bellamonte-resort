document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll("input, select, textarea");

    inputs.forEach((input) => {
        input.addEventListener("click", function () {
            this.focus();

            if (this.type === "date") {
                this.showPicker?.();
            }
        });
    });
});
