document.addEventListener('DOMContentLoaded', function () {
    const consentPopup = document.getElementById('cookie-consent');
    const acceptButton = document.getElementById('accept-cookies');
    const rejectButton = document.getElementById('reject-cookies');

    // Check if user has already given consent
    if (!getCookie('cookie_consent')) {
        consentPopup.style.display = 'block';  // Show the consent popup
    }

    // Handle Accept button click
    acceptButton.addEventListener('click', function () {
        setCookie('cookie_consent', 'accepted', 30);  // Set the consent cookie for 30 days
        consentPopup.style.display = 'none';  // Hide the consent popup
        sendConsentToBackend('accepted');  // Send consent to the backend
    });

    // Handle Reject button click
    rejectButton.addEventListener('click', function () {
        setCookie('cookie_consent', 'rejected', 30);  // Set the reject cookie
        consentPopup.style.display = 'none';  // Hide the consent popup
        sendConsentToBackend('rejected');  // Send rejection to the backend
    });

    // Function to send data to the backend
    function sendConsentToBackend(consent) {
        let pageStartTime = Date.now();

        window.addEventListener('beforeunload', function() {
            let timeSpent = Date.now() - pageStartTime;

            fetch("{{ route('store-user-behavior') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    cookie_consent: consent,  // Consent value
                    time_spent: timeSpent,    // Time spent on the page
                    page: window.location.href,  // Page URL
                    date: new Date().toISOString(),  // Current date
                    user_name: '{{ Auth::check() ? Auth::user()->name : "Guest" }}',  // Send the user name or "Guest"
                })
            }).then(response => {
                console.log('Consent and user behavior data sent:', consent);
            }).catch(error => {
                console.error('Error sending data:', error);
            });
        });
    }

    // Function to set a cookie
    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000)); // Set expiration time
        const expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";  // Set cookie
    }

    // Function to get a cookie value
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;  // Return null if cookie is not found
    }
});
