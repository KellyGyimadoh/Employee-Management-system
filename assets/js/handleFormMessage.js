import alertFunction from './alertFunction.js'
export default function handleFormMessage(data) {
    const errorinfomsg = document.querySelector(".errormsg");
    try {
        
            if (data.success && data.redirecturl) {
                alertFunction(data.message, data.success)
                setTimeout(() => {
                    window.location.href = data.redirecturl
                }, 3000);


            } else if (data.success && data.redirecturl == null) {
                alertFunction(data.message, data.success)
                setTimeout(() => {
                    window.location.reload()
                }, 3000);

            } else {
                alertFunction(data.message, data.success)
                errorinfomsg.innerHTML = data.errors ? Object.values(data.errors).join("<br>") : data.message;

            }


    } catch (error) {
        alertFunction(error, false)
    }
}