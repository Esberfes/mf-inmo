$(document).ready(function() {
    window.Echo.channel("locales")
        .listen("LocalActualizadoEvent", data => {
            var toast = $(`<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
                <div class="toast-header">
                    <div class="d-flex flex-column">
                        <small class="text-muted">Actualización</small>
                        <div><strong class="mr-auto">${data.local.titulo}</strong></div>
                    </div>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    <a href='/directorio/${data.local.url_amigable}'>Ver actualización</a>
                </div>
            </div>`);

            console.log("Actualizado", data);

            $("body").append(toast);
            toast.toast();
            toast.toast("show");
            setTimeout(() => {
                toast.remove();
            }, 10000);
        })
        .listen("LocalCreadoEvent", data => {
            var toast = $(`<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
                <div class="toast-header">
                    <div class="d-flex flex-column">
                        <small class="text-muted">Nuevo local</small>
                        <div><strong class="mr-auto">${data.local.titulo}</strong></div>
                    </div>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    <a href='/directorio/${data.local.url_amigable}'>Ver nuevo local</a>
                </div>
            </div>`);

            console.log("Creado", data);

            $("body").append(toast);
            toast.toast();
            toast.toast("show");
            setTimeout(() => {
                toast.remove();
            }, 10000);
        });

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
