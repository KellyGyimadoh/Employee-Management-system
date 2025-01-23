export default function alertFunction(message,success){
    const alertbox=document.querySelector(".alertbox")
    const alertmsg=document.querySelector(".alertmessage")
        if(success){
        alertbox.classList.remove('alertdanger')
        alertbox.classList.add('alertsuccess')
        alertbox.style.display="block";
        alertmsg.innerHTML=message; 
        }else{
        alertbox.classList.remove('alertsuccess')
        alertbox.classList.add('alertdanger')
        alertbox.style.display="block";
        alertmsg.innerHTML=message;
        }
       
} 