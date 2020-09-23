let myCacheName = 'CacheV1.0';
let cachedFiles = [
    '.',
    'wp-content/themes/kongres/manifest.php',
    'wp-content/themes/kongres/assets/js/main123.js',
    'wp-content/themes/kongres/assets/js/swScript.js',
    'wp-content/themes/kongres/assets/img/android-chrome-192x192-maskable.png',
    'wp-content/themes/kongres/assets/img/android-chrome-512x512.png',
    'wp-content/themes/kongres/assets/img/apple-touch-icon.png',
    'wp-content/themes/kongres/assets/img/favicon.ico',
];

self.addEventListener('install', async function (evt) {
    console.log('Service worker install event');

    // Add the files to the cache
    evt.waitUntil(addFilesToCache());

    sendMessage();
});

async function addFilesToCache() {
    try {
        let cache = await caches.open(myCacheName);
        console.log("Cache:", cache);
        await cache.addAll(cachedFiles);
        await self.skipWaiting();
    } catch (e) {
        console.log('Cache failed', e);
    }
}

async function sendMessage() {
    let clientsArray = await self.clients.matchAll({includeUncontrolled: true, type: 'window'});
    // console.log("clients", clientsArray);

    for (let client of clientsArray) {
        // client.postMessage(JSON.stringify({type: response.url, data: jsonResponse.data}));
        client.postMessage("This is a test message");
        // console.log("postMessage");
    }
}

self.addEventListener('activate', function (evt) {
    console.log('Service worker activate event');

    evt.waitUntil(updateCachedFiles());
    return self.clients.claim();
});

async function updateCachedFiles() {
    let cacheNamesStringArray = await caches.keys();
    console.log("Cache names array:", cacheNamesStringArray);

    for (let cacheName of cacheNamesStringArray) {
        if (cacheName !== myCacheName) {
            console.log('Removing old cache', cacheName);
            let cacheRemovedBoolean = await caches.delete(cacheName);
            console.log('Cache removed', cacheRemovedBoolean); // This will always be true, since the cache names are gotten from the keys() method, so it's guaranteed that it exists
        }
    }
}

self.addEventListener('fetch', function (event) {
    // console.log('Fetch event ' + event.request.url);

    // Only match files, i.e. URLs containing a . (e.g. http://localhost/kongres-poc/wp-content/themes/kongres/assets/js/swScript.js)
    // We normally can't match the front page (e.g. http://localhost/kongres-poc/), since the nonce (which is placed in the HTML by "wp_enqueue_script('wp-api');" (which calls (wp_localize_script)) must be updated with every refresh
    // However, if the user is offline, the front page can be matched (since the nonce doesn't matter in this case)
    let regex = /\./;
    if (regex.test(event.request.url) || !navigator.onLine)
        event.respondWith(getResponse(event));

    // if (event.request.url !== 'http://localhost/kongres-poc' && event.request.url !== 'http://localhost/kongres-poc/')
    //     event.respondWith(getResponse(event));
});

async function getResponse(event) {
    try {
        let cachedResponse = await caches.match(event.request);
        return cachedResponse || fetch(event.request); // "Cache first strategy" - If a cached response exists, return it. If not, fetch the resource via the network
    } catch (e) {
        console.log('Response failed', e);
    }
}