
import fetchData from "./fetchData.js";
document.addEventListener("DOMContentLoaded", () => {

    const recordsPerPagePayroll=document.getElementById("recordsPerPage");
    const payrollTable=document.getElementById("payrollTableBody");
    const searchdptForm=document.getElementById("searchSalary");
    const paginator=document.querySelector(".payrollpagination")

    let recordsPerPageforPayroll=recordsPerPagePayroll.value;
    
    let currentPage = 1;
    let searchQuery = "";

    // Fetch and render data
   
  
   (async ()=>{
    const payrollData= await fetchData('../../api/payroll/process.fetchallpayroll.php',currentPage,recordsPerPageforPayroll,searchQuery)
    if(payrollData){
       
        renderpayrollTable(payrollData.payrolls)
        renderPayrollPaginator(payrollData.pagination.total_pages,payrollData.pagination.current_page) }
   })()


    
//departments
const renderpayrollTable=(payrolls)=>{
            
    payrollTable.innerHTML= payrolls.map((payroll,index)=>
            `<tr>

            <td>${(currentPage-1) * recordsPerPageforPayroll+ index}</td>
            <td>${ payroll.firstname +" "+  payroll.lastname}</td>
            <td>${payroll.date}</td>
            <td>${payroll.total_salary}</td>
            <td>${payroll.status}</td>
            <td>
            <a class="btn btn-primary"
             href="../api/payroll/process.editpayroll.php?payrollid=${payroll.id}&userid=${payroll.user_id}">Edit</a>
            </td>
            </tr>`
    )
}
const renderPayrollPaginator=(totalpages,currentpage)=>{
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

recordsPerPagePayroll.addEventListener("change",(e)=>{
    recordsPerPageforPayroll=e.target.value
    currentPage=1
    (async ()=>{
        const payrollData= await fetchData('../../api/payroll/process.fetchallpayroll.php',currentPage,recordsPerPageforPayroll,searchQuery)
        if(payrollData){
           
            renderpayrollTable(payrollData.salaries)
            renderPayrollPaginator(payrollData.pagination.total_pages,payrollData.pagination.current_page) }
       })()
    
    })


searchdptForm.addEventListener("submit",(e)=>{
    e.preventDefault()
    searchQuery= new FormData(searchdptForm).get('search');
    currentPage=1;
    let recordsPerPageforPayroll=recordsPerPagePayroll.value;
    (async ()=>{
        const payrollData= await fetchData('../../api/payroll/process.fetchallpayroll.php',currentPage,recordsPerPageforPayroll,searchQuery)
        if(payrollData){
           
            renderpayrollTable(payrollData.salaries)
            renderPayrollPaginator(payrollData.pagination.total_pages,payrollData.pagination.current_page) }
       })()
    
})

paginator.addEventListener("click",(e)=>{
    const page=e.target.getAttribute("data-page")
    let recordsPerPageforPayroll=recordsPerPagePayroll.value;
    if(page){
        currentPage=parseInt(page);
        (async ()=>{
            const payrollData= await fetchData('../../api/payroll/process.fetchallpayroll.php',currentPage,recordsPerPageforPayroll,searchQuery)
            if(payrollData){
               
                renderpayrollTable(payrollData.salaries)
                renderPayrollPaginator(payrollData.pagination.total_pages,payrollData.pagination.current_page) }
           })()
        }        
})
  
   

})