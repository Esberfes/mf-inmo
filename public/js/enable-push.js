var version = "20190516.1341";

document.addEventListener("DOMContentLoaded", function(event) {
    var actualizar = false;
    if (navigator.serviceWorker.controller) {
        var versioncargada = "";

        navigator.serviceWorker
            .getRegistrations()
            .then(function(registrations) {
                for (let registration of registrations) {
                    console.log(registration);
                    versioncargada = registration.active.scriptURL.split(
                        "?"
                    )[1];
                    if (versioncargada != version) {
                        actualizar = true;
                        console.log(
                            "[PWA Builder] old active service worker found, updating..."
                        );
                        registration
                            .unregister()
                            .then(function() {
                                return self.clients.matchAll();
                            })
                            .then(function(clients) {
                                clients.forEach(client => {
                                    if (client.url && "navigate" in client) {
                                        client.navigate(client.url);
                                    }
                                });
                            });
                    } else {
                        actualizar = false;
                        console.log(
                            "[PWA Builder] active service worker found, no need to register"
                        );
                    }
                }

                if (actualizar) registerSW();
            });
    } else {
        actualizar = true;
        console.log("[PWA Builder] dont found...");
        registerSW();
    }

    subscribeUser();
});

function registerSW() {
    navigator.serviceWorker
        .register("/sw.js?" + version, {
            scope: "/"
        })
        .then(function(reg) {
            console.log(
                "Service worker has been registered for scope:" + reg.scope
            );

            reg.pushManager.getSubscription().then(function(sub) {
                if (sub === null) {
                    // Update UI to ask user to register for Push
                    console.log("Not subscribed to push service!");
                } else {
                    // We have a subscription, update the database
                    console.log("Subscription object: ", sub);
                }
            });
        });
}

const applicationServerPublicKey =
    "BJx-qpyinNLibmISlxvwK2DkHI9buwJxKjDKjBsALiTBjd96bfq0Rl0r-5A5pUTonSVVDxyj6O8fBsbxnDP37rg";
const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);

function urlB64ToUint8Array(base64String) {
    const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, "+")
        .replace(/_/g, "/");

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

Notification.requestPermission(function(status) {
    console.log("Notification permission status:", status);
});

function displayNotification() {
    if (Notification.permission == "granted") {
        navigator.serviceWorker.getRegistration().then(function(reg) {
            var options = {
                body: "Existe nueva información en mfInmobiliaria",
                icon: "/img/pwa/icon-32x32.png",
                vibrate: [100, 50, 100],
                data: {
                    dateOfArrival: Date.now(),
                    primaryKey: 1
                },
                actions: [
                    {
                        action: "explore",
                        title: "Nueva información enmfInmobiliaria",
                        icon: "/img/pwa/icon-32x32.png"
                    },
                    {
                        action: "close",
                        title: "Cerrar",
                        icon: "images/xmark.png"
                    }
                ]
            };
            reg.showNotification("mfInmobiliaria", options);
        });
    }
}

function subscribeUser() {
    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.ready.then(function(reg) {
            reg.pushManager
                .subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey
                })
                .then(function(sub) {
                    console.log("Endpoint URL: ", sub.endpoint);
                    updateSubscriptionOnServer(sub);
                })
                .catch(function(e) {
                    if (Notification.permission === "denied") {
                        console.warn("Permission for notifications was denied");
                    } else {
                        console.error("Unable to subscribe to push", e);
                    }
                });
        });
    }
}
function updateSubscriptionOnServer(subscription) {
    // TODO: Send subscription to application server

    const subscriptionJson = document.querySelector(".js-subscription-json");
    const subscriptionDetails = document.querySelector(
        ".js-subscription-details"
    );

    if (subscription) {
        const userTokenEl = document.getElementById("userToken");
        const key = subscription.getKey("p256dh");
        const token = subscription.getKey("auth");
        const userToken = userTokenEl ? userTokenEl.value : "";
        const contentEncoding = (PushManager.supportedContentEncodings || [
            "aesgcm"
        ])[0];
        var bodystring = JSON.stringify({
            endpoint: subscription.endpoint,
            publicKey: key
                ? btoa(String.fromCharCode.apply(null, new Uint8Array(key)))
                : null,
            authToken: token
                ? btoa(String.fromCharCode.apply(null, new Uint8Array(token)))
                : null,
            contentEncoding,
            userToken: userToken
        });
        //subscriptionJson.textContent = bodystring;
        //subscriptionJson.textContent = subscription.endpoint;
        const cres = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        fetch("/push", {
            method: "POST",
            body: bodystring,
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-CSRF-Token": cres
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

        //subscriptionDetails.classList.remove('is-invisible');
    }
    //subscriptionDetails.classList.add('is-invisible');
}
