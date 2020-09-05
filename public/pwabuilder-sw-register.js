if ("serviceWorker" in navigator) {
    if ("PushManager" in window) {
        navigator.serviceWorker
            .register("/pwabuilder-sw.js")
            .then(() => {
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
            })
            .catch(err => {
                console.log(err);
            });
    } else {
        console.error("No PushManager allowed");
    }
} else {
    console.error("No serviceWorker allowed");
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
