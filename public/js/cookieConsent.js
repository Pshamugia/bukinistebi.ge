document.addEventListener('DOMContentLoaded', function () {
    const consentPopup = document.getElementById('cookie-consent');
    const acceptButton = document.getElementById('accept-cookies');
    const rejectButton = document.getElementById('reject-cookies');

    const config = window.cookieConsentConfig || {};
    const savedConsent = getCookie('cookie_consent');

    let pageStartTime = Date.now();
    let lastVisibleTime = pageStartTime;
    let totalVisibleTime = 0;

    // Track if tab becomes hidden or visible
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            totalVisibleTime += Date.now() - lastVisibleTime;
        } else {
            lastVisibleTime = Date.now();
        }
    });

    // Show consent popup if no prior response
    if (!savedConsent) {
        consentPopup.style.display = 'block';
    }

    // Accept cookies
    acceptButton?.addEventListener('click', function () {
        setCookie('cookie_consent', 'accepted', 30);
        consentPopup.style.display = 'none';
        sendConsent('accepted');
    });

    // Reject cookies
    rejectButton?.addEventListener('click', function () {
        setCookie('cookie_consent', 'rejected', 30);
        consentPopup.style.display = 'none';
        sendConsent('rejected');
    });

    // Always send session info when user leaves
    window.addEventListener('beforeunload', function () {
        if (document.visibilityState === 'visible') {
            totalVisibleTime += Date.now() - lastVisibleTime;
        }

        fetch(config.storeUrl || '/store-cookie-consent', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": config.csrf
            },
            body: JSON.stringify({
                cookie_consent: getCookie('cookie_consent') || 'unknown',
                time_spent: Math.floor(totalVisibleTime / 1000),
                page: window.location.href,
                date: new Date().toISOString().split('T')[0],
                user_name: config.user_name || 'Guest'
            })
        });
    });

    function sendConsent(value) {
        totalVisibleTime += Date.now() - lastVisibleTime;

        fetch(config.storeUrl || '/store-cookie-consent', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": config.csrf
            },
            body: JSON.stringify({
                cookie_consent: value,
                time_spent: Math.floor(totalVisibleTime / 1000),
                page: window.location.href,
                date: new Date().toISOString().split('T')[0],
                user_name: config.user_name || 'Guest'
            })
        })
        .then(res => res.json())
        .then(data => console.log('Consent saved:', data))
        .catch(err => console.error('Consent error:', err));
    }

    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = `${name}=${value};${expires};path=/`;
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
});
