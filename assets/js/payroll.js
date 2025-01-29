
import fetchData from "./fetchData.js";
import processForm  from './processForm.js';
import handleFormMessage from './handleFormMessage.js'
import getCsrfToken from "./getCsrfToken.js";
document.addEventListener("DOMContentLoaded", () => {

    const recordsPerPagePayroll=document.getElementById("recordsPerPage");
    const payrollTable=document.getElementById("payrollTableBody");
    const searchPayrollForm=document.getElementById("searchPayroll");
    const paginator=document.querySelector(".payrollpagination")
    

    payrollTable.addEventListener("submit", async (e) => {
        if (e.target && e.target.matches("form[id^='makepayment-form']")) {
            e.preventDefault();
            const form = e.target;
            const resultData = await processForm(form, '../../api/payroll/process.updatepayment.php');
            handleFormMessage(resultData);
        }
    });
    

    let recordsPerPageforPayroll=recordsPerPagePayroll.value;
    
    let currentPage = 1;
    let searchQuery = "";
    let searchdate="";

    // Fetch and render data
   
  
   (async ()=>{
    const payrollData= await fetchData('../../api/payroll/process.fetchallpayroll.php',currentPage,recordsPerPageforPayroll,searchQuery,null,searchdate)
    if( payrollData?.payrolls ){
       
        renderpayrollTable(payrollData.payrolls)
        renderPayrollPaginator(payrollData.pagination.total_pages,payrollData.pagination.current_page)
    }else{
        payrollTable.innerHTML+=`<tr>Oops no records found</tr>`
    }
   })()


    
//departments
const renderpayrollTable=(payrolls)=>{
            
    payrollTable.innerHTML= payrolls.map((payroll,index)=>
            `<tr>

            <td>${(currentPage-1) * recordsPerPageforPayroll+ index}</td>
            <td>${ payroll.firstname +' '+  payroll.lastname}</td>
            <td>${payroll.total_salary}</td>
            <td>${payroll.due_date?payroll.due_date:"N/A"}</td>
            <td>${payroll.date?payroll.date : "N/A" }</td>
            <td>${checkStatus(payroll.status)}</td>
            <td>
            <a class='btn btn-primary'
             href='../api/payroll/process.editpayroll.php?payrollid=${payroll.id}&userid=${payroll.user_id}'>Edit</a>
            </td>
            <td>${paymentForm(payroll.id,payroll.status)}</td>
            </tr>`
    ).join("")
}
function checkStatus(status){
    let button;
    switch(status){
        case 'unpaid':
            button=`<button type='button' class='btn btn-danger'>Unpaid</button>`
        break;
        case 'paid':
            button= ` <button type='button' class='btn btn-success'>Paid</button>`
        break;
        case 'pending':
            button= ` <button type='button' class='btn btn-warning'>Pending</button>`
        break;
        default:
            button='N/A'
        

    }
    return button;
}

function paymentForm(id,status)
{
        let payrollform;
        switch(status){
            case 'unpaid':
                payrollform=`
                <form id='makepayment-form-${id}' method='post'>
                <input type='hidden' name='id' value='${id}'/>
                 <input type="hidden" name="csrf_token" value='${getCsrfToken()}'>
                <button  class='btn btn-primary'>Make Payment</button>
                </form>
                `
            break;
            case 'paid':
                payrollform= `<button type='button' class='btn btn-success'>Complete</button>`
            break;
            case 'pending':
                payrollform=`
                <form id='makepayment-form-${id}'>
                <input type='hidden' name='id' value='${getCsrfToken()}'/>
                 <input type="hidden" name="csrf_token">
                <button  class='btn btn-primary'>Make Payment</button>
                </form>
                `
            break;
            default:
                payrollform='N/A'
        }
        return payrollform;
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
        const payrollData= await fetchData('../../api/payroll/process.fetchallpayroll.php',currentPage,recordsPerPageforPayroll,searchQuery,null,searchdate)
        if(payrollData){
           
            renderpayrollTable(payrollData.payrolls)
            renderPayrollPaginator(payrollData.pagination.total_pages,payrollData.pagination.current_page) }
       })()
    
    })


searchPayrollForm.addEventListener("submit",(e)=>{
    e.preventDefault()
    searchQuery= new FormData(searchPayrollForm).get('search');
    searchdate= new FormData(searchPayrollForm).get('searchdate');
    currentPage=1;
    let recordsPerPageforPayroll=recordsPerPagePayroll.value;
    (async ()=>{
        const payrollData= await fetchData('../../api/payroll/process.fetchallpayroll.php',currentPage,recordsPerPageforPayroll,searchQuery,null,searchdate)
        if(payrollData?.payrolls){
           
            renderpayrollTable(payrollData.payrolls)
            renderPayrollPaginator(payrollData.pagination.total_pages,payrollData.pagination.current_page) 
        }else{
            payrollTable.innerHTML+=`<tr>Oops no records found</tr>`
        }
       })()
    
})

paginator.addEventListener("click",(e)=>{
    const page=e.target.getAttribute("data-page")
    let recordsPerPageforPayroll=recordsPerPagePayroll.value;
    if(page){
        currentPage=parseInt(page);
        (async ()=>{
            const payrollData= await fetchData('../../api/payroll/process.fetchallpayroll.php',currentPage,recordsPerPageforPayroll,searchQuery,null,searchdate)
            if(payrollData && payrollData.payrolls !== null){
               
                renderpayrollTable(payrollData.payrolls)
                renderPayrollPaginator(payrollData.pagination.total_pages,payrollData.pagination.current_page) 
            }else{
                payrollTable.innerHTML+=`<tr>Oops no records found</tr>`
            }

           })()
        }        
})
  
   

})