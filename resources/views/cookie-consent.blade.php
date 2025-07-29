<!-- Cookie Consent Popup -->
<div id="cookie-consent-popup" style="display: block;">
    <p>We use cookies to improve your experience. By using our site, you accept our use of cookies.</p>
    <button id="accept-cookie">Accept</button>
    <button id="reject-cookie">Reject</button>
</div>

<script>
    // Check if the cookie consent is already stored in localStorage
    if (localStorage.getItem('cookie-consent') !== 'accepted') {
        document.getElementById("cookie-consent-popup").style.display = "block";
    } else {
        document.getElementById("cookie-consent-popup").style.display = "none";
    }

    // Handle Accept button
    document.getElementById("accept-cookie").addEventListener("click", function() {
        localStorage.setItem("cookie-consent", "accepted");
        document.getElementById("cookie-consent-popup").style.display = "none";
    });

    // Handle Reject button
    document.getElementById("reject-cookie").addEventListener("click", function() {
        localStorage.setItem("cookie-consent", "rejected");
        document.getElementById("cookie-consent-popup").style.display = "none";
    });
</script>
