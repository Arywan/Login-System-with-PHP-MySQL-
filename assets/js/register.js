document.addEventListener("DOMContentLoaded", function() {
    // Get the query parameters from the URL
    const urlParams = new URLSearchParams(window.location.search);
    
    // Check if the 'success' parameter is present in the URL
    if (urlParams.has('success')) {
        // Get the value of the 'success' parameter
        const success = urlParams.get('success');
        
        // Show an alert based on the value of the 'success' parameter
        if (success === '1') {
            alert("Registration successful. You can now login.");
        } else if (success === '0') {
            alert("Registration failed. Please try again later.");
        }
    }
});
