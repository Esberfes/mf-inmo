@extends('admin.admin-layout')

@section('content')


@if ($errors->any())
<div class="alert alert-danger mt-3">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session()->has('success'))
<div class="alert alert-success mt-3">
    {{ session()->get('success') }}
</div>
@endif
<div class="card mb-3">
    <div class="card-header mb-3">Configuración</div>
    <div class="card-body">
        <button class="btn btn-primary" id="accept-push">Activar notificaciones</button>
        <button class="btn btn-primary" id="disable-push">Desactivar notificaciones</button>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $("#accept-push").click(function() {
        alert("Suscripción solicitada");
        askPermission();
    });

    $("#disable-push").click(function() {
        if (confirm("Seguro que desea cancela la suscripción?")) {
            navigator.serviceWorker.ready.then(function(reg) {
                reg.pushManager.getSubscription().then(function(subscription) {
                    subscription.unsubscribe().then(function(successful) {

                    }).catch(function(e) {
                        //Unsubscription failed
                    })
                })
                alert("Suscripción eliminada");
                fetch("/push", {
                        method: "DELETE",
                        headers: {
                            Accept: "application/json",
                            "Content-Type": "application/json",
                            "X-CSRF-Token": document.querySelector("meta[name=csrf-token]").getAttribute("content")
                        }
                    })
                    .then(res => {
                        return res.json();
                    })
                    .then(res => {
                        console.log(res);
                    })
                    .catch(err => {
                        console.log(err);
                    });
            });
        }

    })

    function askPermission() {
        console.log(
            "serviceWorker installed, activating notifications...."
        );
        if (navigator.serviceWorker.ready) {
            new Promise(function(resolve, reject) {
                const permissionResult = Notification.requestPermission(
                    function(result) {
                        resolve(result);
                    }
                );

                if (permissionResult) {
                    permissionResult.then(resolve, reject);
                }
            }).then(permissionResult => {
                if (permissionResult !== "granted") {
                    throw new Error("We weren't granted permission.");
                }
                console.log("Subscribing user...");
                navigator.serviceWorker.ready
                    .then(registration => {
                        const subscribeOptions = {
                            userVisibleOnly: true,
                            applicationServerKey: urlBase64ToUint8Array(
                                "BJx-qpyinNLibmISlxvwK2DkHI9buwJxKjDKjBsALiTBjd96bfq0Rl0r-5A5pUTonSVVDxyj6O8fBsbxnDP37rg"
                            )
                        };

                        return registration.pushManager.subscribe(
                            subscribeOptions
                        );
                    })
                    .then(pushSubscription => {
                        console.log(
                            "Received PushSubscription: ",
                            JSON.stringify(pushSubscription)
                        );
                        storePushSubscription(pushSubscription);
                    });
            });
        } else {
            console.error(
                "Aborting activate notifcations, serviceWorker is not ready"
            );
        }
    }

    function urlBase64ToUint8Array(base64String) {
        var padding = "=".repeat((4 - (base64String.length % 4)) % 4);
        var base64 = (base64String + padding)
            .replace(/\-/g, "+")
            .replace(/_/g, "/");

        var rawData = window.atob(base64);
        var outputArray = new Uint8Array(rawData.length);

        for (var i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    function storePushSubscription(pushSubscription) {
        const csrfToken = document
            .querySelector("meta[name=csrf-token]")
            .getAttribute("content");

        console.log("pushSubscription", pushSubscription)

        const endpoint = pushSubscription.endpoint;
        const key = pushSubscription.getKey('p256dh');
        const token = pushSubscription.getKey('auth');
        const encoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];


        fetch("/push", {
                method: "POST",
                body: JSON.stringify({
                    endpoint: endpoint,
                    key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                    auth: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                    encoding,
                }),
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-Token": csrfToken
                }
            })
            .then(res => {
                return res.json();
            })
            .then(res => {
                console.log(res);
            })
            .catch(err => {
                console.log(err);
            });
    }
</script>
@endsection
