document.addEventListener('DOMContentLoaded', function () {
    const consentPopup = document.getElementById('cookie-consent');
    const acceptButton = document.getElementById('accept-cookies');
    const rejectButton = document.getElementById('reject-cookies');

    const config = window.cookieConsentConfig || {};
    const pageStartTime = Date.now();

    if (!getCookie('cookie_consent')) {
        consentPopup.style.display = 'block';
    }

    acceptButton?.addEventListener('click', function () {
        setCookie('cookie_consent', 'accepted', 30);
        consentPopup.style.display = 'none';
        sendConsent('accepted');
    });

    rejectButton?.addEventListener('click', function () {
        setCookie('cookie_consent', 'rejected', 30);
        consentPopup.style.display = 'none';
        sendConsent('rejected');
    });

    function sendConsent(value) {
        const timeSpent = Math.floor((Date.now() - pageStartTime) / 1000);

        fetch(config.storeUrl || '/store-cookie-consent', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": config.csrf
            },
            body: JSON.stringify({
                cookie_consent: value,
                time_spent: timeSpent,
                page: window.location.href,
                date: new Date().toISOString().split('T')[0],
                user_name: config.user_name || 'Guest'
            })
        })
        .then(res => res.json())
        .then(data => console.log('Saved:', data))
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