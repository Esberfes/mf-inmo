if ("serviceWorker" in navigator) {
    if ("PushManager" in window) {
        navigator.serviceWorker
            .register("/pwabuilder-sw.js")
            .then(() => {

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
