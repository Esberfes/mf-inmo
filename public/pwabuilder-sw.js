const CACHE = "pwabuilder-offline";

self.addEventListener("message", event => {
    if (event.data && event.data.type === "SKIP_WAITING") {
        self.skipWaiting();
    }
});

var urlsToCache = ["/*"];

var currentCache = "MFCacheV1";

self.addEventListener("install", function(event) {
    self.skipWaiting();

    event.waitUntil(
        urlsToCache.forEach(function(url) {
            var indexPage = new Request(url);
            fetch(indexPage).then(function(response) {
                response
                    .clone()
                    .blob()
                    .then(blob =>
                        console.log(
                            url + " >>>> bytes: " + readableBytes(blob.size, 1)
                        )
                    );
                return caches.open(currentCache).then(function(cache) {
                    console.log(
                        "[PWA Builder] Cached index page during Install" +
                            response.url
                    );
                    return cache.put(indexPage, response);
                });
            });
        })
    );
});

const readableBytes = (bytes, i) => {
    const sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    return (bytes / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + sizes[i];
}

self.addEventListener("fetch", function(event) {
    event.respondWith(
        fetch(event.request).catch(function() {
            return caches.match(event.request);
        })
    );
});

self.addEventListener("activate", function(event) {
    var cacheWhitelist = [currentCache];

    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        console.log("Removing cache >>> " + cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    event.waitUntil(clients.claim());
});

self.addEventListener("notificationclose", function(e) {
    var notification = e.notification;
    var primaryKey = notification.data.primaryKey;

    console.log("Closed notification: " + primaryKey);
});

self.addEventListener("notificationclick", function(e) {
    var notification = e.notification;
    var action = e.action;

    if (action === "close") {
        notification.close();
    } else {
        clients.openWindow("/");
        notification.close();
    }
});

self.addEventListener("push", function(e) {
    if (!(self.Notification && self.Notification.permission === "granted")) {
        //notifications aren't supported or permission not granted!
        return;
    }

    if (e.data) {
        var msg = e.data.json();
        console.log(msg);
        e.waitUntil(
            self.registration.showNotification(msg.title, {
                body: msg.body,
                icon: msg.icon,
                actions: msg.actions
            })
        );
    }
});
