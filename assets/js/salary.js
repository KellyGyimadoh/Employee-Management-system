
import fetchData from "./fetchData.js";
document.addEventListener("DOMContentLoaded", () => {

    const recordsPerPageSalary=document.getElementById("recordsPerPage");
    const salaryTable=document.getElementById("salaryTableBody");
    const searchdptForm=document.getElementById("searchSalary");
    const paginator=document.querySelector(".salarypagination")

    let recordsPerPageforSalary=recordsPerPageSalary.value;
    
    let currentPage = 1;
    let searchQuery = "";

    // Fetch and render data
   
  
   (async ()=>{
    const salaryData= await fetchData('../../api/salaries/process.viewtablesalaries.php',currentPage,recordsPerPageforSalary,searchQuery)
    if(salaryData){
       
        renderSalaryTable(salaryData.salaries)
        renderSalaryPaginator(salaryData.pagination.total_pages,salaryData.pagination.current_page) }
   })()


    
//departments
const renderSalaryTable=(salaries)=>{
            
    salaryTable.innerHTML= salaries.map((salary,index)=>
            `<tr>

            <td>${(currentPage-1) * recordsPerPageforSalary+ index}</td>
            <td>${ salary.firstname +" "+  salary.lastname}</td>
            <td>${salary.departments.split(" ").map(dept => `<span>${dept}</span><br>`)
                    .join("")}</td>
            <td>${salary.base_salary}</td>
            <td>${salary.bonus}</td>
            <td>${salary.overtime }</td>
            <td>${salary.deductions}</td>
            <td>${salary.total_salary}</td>
            <td>${salary.status===1 ? `<button class="btn btn-success">Active</button>`
            :`<button class="btn btn-danger">Suspended</button>`}</td>
            
            <td>
            <a class="btn btn-primary" href="../api/salaries/process.editsalary.php?salaryid=${salary.id}&userid=${salary.user_id}">Edit</a>
            </td>
            </tr>`
    ).join("")
}
const renderSalaryPaginator=(totalpages,currentpage)=>{
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

recordsPerPageSalary.addEventListener("change",(e)=>{
    recordsPerPageforSalary=e.target.value
    currentPage=1
    (async ()=>{
        const salaryData= await fetchData('../../api/salaries/process.viewtablesalaries.php',currentPage,recordsPerPageforSalary,searchQuery)
        if(salaryData){
           
            renderSalaryTable(salaryData.salaries)
            renderSalaryPaginator(salaryData.pagination.total_pages,salaryData.pagination.current_page) }
       })()
    
    })


searchdptForm.addEventListener("submit",(e)=>{
    e.preventDefault()
    searchQuery= new FormData(searchdptForm).get('search');
    currentPage=1;
    let recordsPerPageforSalary=recordsPerPageSalary.value;
    (async ()=>{
        const salaryData= await fetchData('../../api/salaries/process.viewtablesalaries.php',currentPage,recordsPerPageforSalary,searchQuery)
        if(salaryData){
           
            renderSalaryTable(salaryData.salaries)
            renderSalaryPaginator(salaryData.pagination.total_pages,salaryData.pagination.current_page) }
       })()
    
})

paginator.addEventListener("click",(e)=>{
    const page=e.target.getAttribute("data-page")
    let recordsPerPageforSalary=recordsPerPageSalary.value;
    if(page){
        currentPage=parseInt(page);
        (async ()=>{
            const salaryData= await fetchData('../../api/salaries/process.viewtablesalaries.php',currentPage,recordsPerPageforSalary,searchQuery)
            if(salaryData){
               
                renderSalaryTable(salaryData.salaries)
                renderSalaryPaginator(salaryData.pagination.total_pages,salaryData.pagination.current_page) }
           })()
        }        
})
  
   

})