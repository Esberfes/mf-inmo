const selectMostrarAlquiler = $("[name='mostrar_compra_alquiler']");
const precioFilter = $("#precio");
const precioAlquilerFilter = $("#precio_alquiler");

const hideOrShowFilters = value => {
    console.log(value);
    switch (value) {
        case "-1":
            console.log("en -1");
            precioFilter.show();
            precioAlquilerFilter.show();
            break;
        case "0":
            console.log("en 0");
            precioFilter.show();
            precioAlquilerFilter.hide();
            break;
        case "1":
            console.log("en 1");
            precioFilter.hide();
            precioAlquilerFilter.show();
            break;
    }
};

hideOrShowFilters(selectMostrarAlquiler.val());

selectMostrarAlquiler.change(e =>
    hideOrShowFilters(selectMostrarAlquiler.val())
);

const selectsWrappers = $(".select-wrapper");

selectsWrappers.each(i => {
    const currentSelectWrapper = $(selectsWrappers.get(i));
    const select = currentSelectWrapper.find("select");

    select.change(() => {
        if (select.val() == "none") {
            currentSelectWrapper.addClass("select-empty");
        } else {
            currentSelectWrapper.removeClass("select-empty");
        }
    });

    if (select.val() == "none") {
        currentSelectWrapper.addClass("select-empty");
    } else {
        currentSelectWrapper.removeClass("select-empty");
    }
});
