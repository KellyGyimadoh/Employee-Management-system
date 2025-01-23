document.addEventListener("DOMContentLoaded",()=>{

    const toggler=document.querySelector(".js-sidebar-toggler")
    const bodybar=document.querySelector(".fixed-navbar");
    
    toggler.addEventListener("click",()=>{
        if(bodybar.classList.contains('sidebar-mini')){
            bodybar.classList.remove('sidebar-mini')
        }else{
            bodybar.classList.add("sidebar-mini")
        }
    })
   
})
