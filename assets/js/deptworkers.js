
import fetchData from "./fetchData.js";
document.addEventListener("DOMContentLoaded", () => {

    const deptid=document.getElementById("deptid");
    const recordsPerPageDpt=document.getElementById("recordsPerPage");
    const departmentWorkersTable=document.getElementById("departmentWorkersTableBody");
    const searchdptForm=document.getElementById("searchdepartment");
    const paginator=document.querySelector(".dptworkerpagination")

    let recordsPerPageforDept=recordsPerPageDpt.value;
    let departmentid=deptid.value;
    
    let currentPage = 1;
    let searchQuery = "";

    // Fetch and render data
   
  
   (async ()=>{
    const deptData= await fetchData('../../api/departments/process.viewdeptworkers.php',currentPage,recordsPerPageforDept,searchQuery,departmentid)
    if(deptData){
       
        renderDeptTable(deptData.departmentworkers)
        renderdeptPaginator(deptData.pagination.total_pages,deptData.pagination.current_page) }
   })()


    
//departments
const renderDeptTable=(workers)=>{
            
    departmentWorkersTable.innerHTML=workers.map((worker,index)=>
            `<tr>

            <td>${(currentPage-1) * recordsPerPageforDept+ index}</td>
            <td>${worker.firstname}</td>
            <td>${worker.lastname}</td>
            <td>${worker.email ? worker.email : 'Not Set'}</td>
            <td>${worker.phone ? worker.phone : "Not Set"}</td>
            <td>${worker.status===1 ? `<button class="btn btn-success">Active</button>`
            :`<button class="btn btn-danger">Suspended</button>`}</td>
            
            <td>
            <a class="btn btn-primary" href=".../../api/userview/process.selectuser.php?userid=${worker.user_id}">Edit</a>
            </td>
            </tr>`
    )
}
const renderdeptPaginator=(totalpages,currentpage)=>{
    paginator.innerHTML="";
    paginator.innerHTML+=`
    <li class="page-item ${currentpage==1 ? "disabled" :"" }">
        <a class="page-link" href="#" data-page=${currentPage-1}>«</a>
    </li>
    `

    for(let i=1; i<=totalpages; i++){
        paginator.innerHTML+=`<li class="page-item ${currentPage===i ? "active" : "" }"} >
        <a class="page-link" href="#" data-page=${i}>${i}</a>
        </li>`
    }

    paginator.innerHTML+=`
    <li class="page-item ${currentpage==totalpages ? "disabled" :"" }">
        <a class="page-link" href="#" data-page=${currentPage+1}>»</a>
    </li>`
}

recordsPerPageDpt.addEventListener("change",(e)=>{
    recordsPerPageforDept=e.target.value
    let departmentid=deptid.value;
    currentPage=1
    (async ()=>{
        const deptWorkersData= await fetchData('../../api/departments/process.viewdeptworkers.php',currentPage,recordsPerPageforDept,null,departmentid)
        if(deptWorkersData){
           
            renderDeptTable(deptWorkersData.departmentworkers)
            renderdeptPaginator(deptWorkersData.pagination.total_pages,deptData.pagination.current_page) }
       })()
    })


searchdptForm.addEventListener("submit",(e)=>{
    e.preventDefault()
    searchQuery= new FormData(searchdptForm).get('search');
    currentPage=1;
    let recordsPerPageforDept=recordsPerPageDpt.value;
    let departmentid=deptid.value;
    (async ()=>{
        const deptWorkersData= await fetchData('../../api/departments/process.viewdeptworkers.php',currentPage,recordsPerPageforDept,null,departmentid)
        if(deptWorkersData){
           
            renderDeptTable(deptWorkersData.departmentworkers)
            renderdeptPaginator(deptWorkersData.pagination.total_pages,deptData.pagination.current_page) }
       })()
})

paginator.addEventListener("click",(e)=>{
    const page=e.target.getAttribute("data-page")
    let recordsPerPageforDept=recordsPerPageDpt.value;
    let departmentid=deptid.value;
    if(page){
        currentPage=parseInt(page);
        (async ()=>{
            const deptWorkersData= await fetchData('../../api/departments/process.viewdeptworkers.php',currentPage,recordsPerPageforDept,null,departmentid)
            if(deptWorkersData){
               
                renderDeptTable(deptWorkersData.departmentworkers)
                renderdeptPaginator(deptWorkersData.pagination.total_pages,deptData.pagination.current_page) }
           })()  
        
        }
})
  
   

})