$(document).ready(() => {
    const popupLifeTiem = 5000;
    const toastContainer = $(`<div class="toast-container"></div>`);
    $("body").append(toastContainer);
    const popups = [];

    window.Echo.channel("locales")
        .listen("LocalActualizadoEvent", data => {
            const toast = createToast(
                "Actualización",
                data.local.titulo,
                `/directorio/${data.local.url_amigable}`,
                "Ver actualización",
                popupLifeTiem
            );
            tryToDisplayPopup(popups, toast, toastContainer);
        })
        .listen("LocalCreadoEvent", data => {
            const toast = createToast(
                "Nuevo local",
                data.local.titulo,
                `/directorio/${data.local.url_amigable}`,
                "Ver nuevo local",
                popupLifeTiem
            );
            console.log("Creado", data);
            tryToDisplayPopup(popups, toast, toastContainer);
        });

    const tryToDisplayPopup = (popups, popup, toastContainer) => {
        if (popups.length <= 2) {
            toastContainer.append(popup);
            popup.toast();
            popup.toast("show");
            popups.push(popup);

            popup.on("hidden.bs.toast", function() {
                removePopup(popups, popup);
            });

            popup.on("hide.bs.toast", function() {
                removePopup(popups, popup);
            });
        } else {
            setTimeout(
                () => tryToDisplayPopup(popups, popup, toastContainer),
                1000
            );
        }
    };

    const removePopup = (popups, popup) => {
        popups.forEach((e, i) => {
            if (e === popup) {
                e.fadeOut(500, function() {
                    popups.splice(i, 1);
                    e.remove();
                });
            }
        });
    };

    const createToast = (title, subTitle, url, urlText, delay) =>
        $(`<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="${delay}">
            <div class="toast-header">
                <div class="d-flex flex-column">
                    <small class="text-muted">${title}</small>
                    <div><strong class="mr-auto">${subTitle}</strong></div>
                </div>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                <a class="btn btn-sm btn-outline-info" href='${url}'>${urlText}</a>
            </div>
        </div>`);

    window.Echo.channel("activities-users").listen("ActivityEvent", data => {
        if (data) {
            if (data.message == "ping") {
                console.log("Discovering on activity channel");

                axios
                    .post("/push/discover_on_activity_channel", {
                        url: window.location.href
                    })
                    .then(response => {
                        console.log(response);
                    })
                    .catch(e => {
                        console.log(e);
                    });
            }
        }
    });

    window.Echo.connector.pusher.connection.bind("connected", () => {
        axios
            .post("/push/discover_on_activity_channel", {
                url: window.location.href
            })
            .then(response => {
                console.log(response);
            })
            .catch(e => {
                console.log(e);
            });
    });
});
