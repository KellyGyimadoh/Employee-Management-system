export default function alertFunction(message, success) {
    const alertbox = document.querySelector(".alertbox");
    const alertmsg = document.querySelector(".alertmessage");

    alertbox.className = `alertbox ${success ? 'alertsuccess' : 'alertdanger'}`;
    alertbox.style.display = "block";
    alertmsg.innerHTML = message;

    setTimeout(() => {
        alertbox.style.display = "none";
        alertbox.classList.add('hidden');
        alertmsg.innerHTML = "";
    }, 3000);
}
