// JavaScript Document

const applicationServerPublicKey = 'BGJYp9rUoAynzCb5ZLPOC1A15rBvCV-v1xByRjuMKmJah8nLhnKbIyPhrg6xdxLex3EZpvGBvWos1yMI13Z06JI';
const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
function urlB64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}

Notification.requestPermission(function(status) {
    console.log('Notification permission status:', status);
});

function displayNotification() {
	if (Notification.permission == 'granted') {
		navigator.serviceWorker.getRegistration().then(function (reg) {
			var options = {
				body: 'Existe nueva información en Proveedores Franquicias',
				icon: 'images/example.png',
				vibrate: [100, 50, 100],
				data: {
					dateOfArrival: Date.now(),
					primaryKey: 1
				},
				actions: [{
					action: 'explore',
					title: 'Nueva información en Proveedores Franquicias',
					icon: 'https://proveedores-franquicias.es/img/layout/logos/icon.png'
				}, {
					action: 'close',
					title: 'Cerrar',
					icon: 'https://proveedores-franquicias.es/img/layout/logos/icon.png'
				}, ]
			};
			reg.showNotification('Proveedores Franquicias', options);
		});
	}
}

function subscribeUser() {
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.ready.then(function(reg) {

      reg.pushManager.subscribe({
        userVisibleOnly: true,
  applicationServerKey: applicationServerKey
      }).then(function(sub) {
        console.log('Endpoint URL: ', sub.endpoint);
			updateSubscriptionOnServer(sub);
      }).catch(function(e) {
        if (Notification.permission === 'denied') {
          console.warn('Permission for notifications was denied');
        } else {
          console.error('Unable to subscribe to push', e);
        }
      });
    })
  }
}
function updateSubscriptionOnServer(subscription) {
  // TODO: Send subscription to application server

  const subscriptionJson = document.querySelector('.js-subscription-json');
  const subscriptionDetails =
    document.querySelector('.js-subscription-details');

  if (subscription) {

 	 const key = subscription.getKey('p256dh');
    const token = subscription.getKey('auth');
    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];

      const elementUserToken = document.getElementById("userToken");
   var bodystring = JSON.stringify({
        endpoint: subscription.endpoint,
        publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
        authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
        contentEncoding,
       userToken: elementUserToken ? elementUserToken.value : ''
      });
	//subscriptionJson.textContent = bodystring;
	  //subscriptionJson.textContent = subscription.endpoint;
	  
	  return fetch('https://push.icons.es/push_subscription.php', {
      method: 'POST',
      body: bodystring,
    }).then(() => subscription);

    //subscriptionDetails.classList.remove('is-invisible');
  } else {
    //subscriptionDetails.classList.add('is-invisible');
  }
}
