const CACHE = "pwabuilder-offline";

importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.0.0/workbox-sw.js');

self.addEventListener("message", (event) => {
  if (event.data && event.data.type === "SKIP_WAITING") {
    self.skipWaiting();
  }
});

workbox.routing.registerRoute(
  new RegExp('/*'),
  new workbox.strategies.StaleWhileRevalidate({
    cacheName: CACHE
  })
);


self.addEventListener('notificationclose', function(e) {
    var notification = e.notification;
    var primaryKey = notification.data.primaryKey;

    console.log('Closed notification: ' + primaryKey);
  });

  self.addEventListener('notificationclick', function(e) {
    var notification = e.notification;
    var primaryKey = notification.data.primaryKey;
    var action = e.action;

    if (action === 'close') {
      notification.close();
    } else {
      clients.openWindow('http://www.example.com');
      notification.close();
    }
  });


  self.addEventListener('push', function(e) {
    console.log('[Service Worker] Push Received.', e);
    console.log('[Service Worker] Push had this data:' + e.data.text() );
    var options = {
      body: e.data.text(),
      icon: '/images/icons/icon-32x32.png',
      vibrate: [100, 50, 100],
      data: {
        dateOfArrival: Date.now(),
        primaryKey: '2'
      },
      actions: [
        {action: 'explore', title: 'Abrir',
          icon: '/images/icons/icon-72x72.png'},
        {action: 'close', title: 'Descartar',
          icon: '/images/icons/icon-72x72.png'},
      ]
    };
    e.waitUntil(
      self.registration.showNotification('mfInmobiliaria', options)
    );
  });
