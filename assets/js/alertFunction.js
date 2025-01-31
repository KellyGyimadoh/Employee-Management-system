export default function alertFunction(message, success) {
    const alertbox = document.querySelector(".alertbox");
    const alertmsg = document.querySelector(".alertmessage");

    // Set the message
    alertmsg.innerHTML = message;

    // Remove any previous alert type and add the new one
    alertbox.className = `alertbox ${success ? 'alertsuccess' : 'alertdanger'}`;

    // Show the alert smoothly
    setTimeout(() => {
        alertbox.classList.add("show");
    }, 50); // Small delay for animation

    // Hide the alert after 3 seconds
    setTimeout(() => {
        alertbox.classList.remove("show");
        setTimeout(() => {
            alertmsg.innerHTML = ""; // Clear message after fade-out
        }, 500);
    }, 3000);
}
