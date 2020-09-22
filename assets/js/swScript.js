if ('serviceWorker' in navigator) {
    window.addEventListener('load', function (event) {
        registerServiceWorker();
    });
} else {
    console.log("No serviceWorker in navigator")
}

async function registerServiceWorker() {
    try {
        let serviceWorkerRegistration = await navigator.serviceWorker.register(
            vars['basePath'] + 'sw.js',
            // {scope: vars['basePath'] + 'vue'}
            );
        console.log('Service worker registered.');
        console.log("Scope", serviceWorkerRegistration.scope);

        navigator.serviceWorker.addEventListener('message', function (event) {
            const message = event.data;
            console.log("message", message);
            // document.getElementById('swMessage').innerText = message;
        });
    } catch (e) {
        console.log('Service worker registration failed.', e);
    }
}

function setupBeforeInstallPrompt() {
    window.addEventListener("beforeinstallprompt", function (event) {
        console.log("beforeinstallprompt fired", event);

        // event.preventDefault(); // Prevent Chrome 67 and earlier from automatically showing the prompt
        // beforeInstallPromptEvent = event; // Stash the event so it can be triggered later.
        // beforeInstallPromptEventFired = true;
        //
        // if (location.pathname === '/')
        //     displayInstallationIcon(); // Update UI notify the user they can install the PWA
    });
}