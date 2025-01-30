
import fetchData from "./fetchData.js";
import getUserId from "./getUserId.js";
document.addEventListener("DOMContentLoaded", () => {

    const recordsPerPageDpt=document.getElementById("recordsPerPage");
    const departmentTable=document.getElementById("departmentTableBody");
    const searchdptForm=document.getElementById("searchdepartment");
    const paginator=document.querySelector(".dptpagination")
    const userId= getUserId();

    let recordsPerPageforDept=recordsPerPageDpt.value;
    
    let currentPage = 1;
    let searchQuery = "";

    // Fetch and render data
   
  
   (async ()=>{
    const deptData= await fetchData('../../api/departments/process.fetchuserdepartment.php',
        currentPage,recordsPerPageforDept,searchQuery,userId,null,null)
    if(deptData){
       
        renderDeptTable(deptData.departments)
        renderdeptPaginator(deptData.pagination.total_pages,deptData.pagination.current_page) }
   })()


    
//departments
const renderDeptTable=(departments)=>{
            
    departmentTable.innerHTML=departments.map(
        (dpt,index)=>
            `<tr>

            <td>${(currentPage-1) * recordsPerPageforDept+ index}</td>
            <td>${dpt.name}</td>
            <td>${dpt.head? dpt.firstname +" "+  dpt.lastname : "N/A"}</td>
            <td>${dpt.email ? dpt.email : 'Not Set'}</td>
            <td>${dpt.phone ? dpt.phone : "Not Set"}</td>
            <td>${dpt.status===1 ? `<button class="btn btn-success">Active</button>`
            :`<button class="btn btn-danger">Suspended</button>`}</td>
            
            <td>
            <a class="btn btn-primary" href="../api/departments/process.editdepartment.php?id=${dpt.id}">View Portal</a>
            </td>
            </tr>`
    ).join("")
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
    currentPage=1
    (async ()=>{
        const deptData= await fetchData('../../api/departments/process.fetchuserdepartment.php',currentPage,
            recordsPerPageforDept,searchQuery,userId,null,null)
        if(deptData){
           
            renderDeptTable(deptData.departments)
            renderdeptPaginator(deptData.pagination.total_pages,deptData.pagination.current_page) }
       })()
    })


searchdptForm.addEventListener("submit",(e)=>{
    e.preventDefault()
    searchQuery= new FormData(searchdptForm).get('search');
    currentPage=1;
    let recordsPerPageforDept=recordsPerPageDpt.value;
    (async ()=>{
        const deptData= await fetchData('../../api/departments/process.fetchuserdepartment.php',
            currentPage,recordsPerPageforDept,searchQuery,userId,null,null)
        if(deptData){
           
            renderDeptTable(deptData.departments)
            renderdeptPaginator(deptData.pagination.total_pages,deptData.pagination.current_page) }
       })()
})

paginator.addEventListener("click",(e)=>{
    const page=e.target.getAttribute("data-page")
    let recordsPerPageforDept=recordsPerPageDpt.value;
    if(page){
        currentPage=parseInt(page);
        (async ()=>{
            const deptData= await fetchData('../../api/departments/process.fetchuserdepartment.php',
                currentPage,recordsPerPageforDept,searchQuery,userId,null,null)
            if(deptData){
               
                renderDeptTable(deptData.departments)
                renderdeptPaginator(deptData.pagination.total_pages,deptData.pagination.current_page) }
           })()   }
})
  
   

})