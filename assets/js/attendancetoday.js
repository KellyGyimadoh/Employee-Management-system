
import fetchData from "./fetchData.js";
import processForm  from './processForm.js';
import handleFormMessage from './handleFormMessage.js'
import getCsrfToken from "./getCsrfToken.js";
document.addEventListener("DOMContentLoaded", () => {

    const recordsPerPageAttendance=document.getElementById("recordsPerPage");
    const attendanceTable=document.getElementById("attendanceTodayTableBody");
    const searchAttendanceForm=document.getElementById("searchTodayAttendance");
    const paginator=document.querySelector(".attendanceTodaypagination")
    

    attendanceTable.addEventListener("submit", async (e) => {
        if (e.target && e.target.matches("form[id^='markattendancetoday-form']")) {
            e.preventDefault();
            const form = e.target;
            const resultData = await processForm(form, '../../api/attendance/process.updateattendance.php');
            handleFormMessage(resultData);
        }

        if(e.target && e.target.matches("form[id^='salarydeduction-form']")){
            e.preventDefault();
           
            const salaryform = e.target;
            const resultData = await processForm(salaryform, '../../api/salaries/process.salarydeduct.php');
            handleFormMessage(resultData);
        }
    });
    // Delegate event listener for inputs inside the dynamically created forms
    attendanceTable.addEventListener("input", (e) => {
        if (e.target && e.target.matches("input[name='amount']")) {
            const deductInput = e.target;
            const button = deductInput.closest("form").querySelector("button");
            button.innerText = `Deduct ${deductInput.value} From Salary`;
        }
    })

    let recordsPerPageforAttendance=recordsPerPageAttendance.value;
    
    let currentPage = 1;
    let searchQuery = "";
    let searchdate="";

    // Fetch and render data
   
  
   (async ()=>{
    const attendanceData= await fetchData('../../api/attendance/process.fetchtodayattendance.php',currentPage,recordsPerPageforAttendance,searchQuery,null,searchdate)
    if( attendanceData?.attendances ){
       
        renderattendanceTable(attendanceData.attendances)
        renderAttendancePaginator(attendanceData.pagination.total_pages,attendanceData.pagination.current_page)
    }else{
        attendanceTable.innerHTML+=`<tr>Oops no records found</tr>`
    }
   })()


    
//departments
const renderattendanceTable=(attendances)=>{
            
    attendanceTable.innerHTML= attendances.map((attendance,index)=>(
            `<tr>

            <td>${(currentPage-1) * recordsPerPageforAttendance+ index}</td>
            <td>${ attendance.firstname +' '+  attendance.lastname}</td>
            <td>${attendance.checkin_time ? attendance.checkin_time : "Not Checked In"}</td>
            <td>${attendance.date ? attendance.date:"N/A"}</td>
            <td>${checkStatus(attendance.status)}</td>
            <td>
            <a class='btn btn-primary'
             href='../../api/attendance/process.editattendance.php?id=${attendance.id}&userid=${attendance.user_id}'>Edit</a>
            </td>
            <td class='w-auto'>${paymentForm(attendance.user_id,attendance.status)}</td>
            </tr>`
    )).join("")
}
function checkStatus(status){
    let button;
    switch(status){
        case 1:
            button=`<button type='button' class='btn btn-danger'>Absent</button>`
        break;
        case 2:
            button= ` <button type='button' class='btn btn-success'>Present</button>`
        break;
        case 3:
            button= ` <button type='button' class='btn btn-warning'>Late</button>`
        break;
        default:
            button='N/A'
        

    }
    return button;
}

function paymentForm(id,status)
{
        let attendanceform;
        switch(status){
            case 1:
                attendanceform=`
                <form id='markattendancetoday-form-${id}' method='post'>
                <input type='hidden' name='id' value='${id}'/>
                 <input type="hidden" name="csrf_token" value='${getCsrfToken()}'>
                <button  class='btn btn-primary'>Mark</button>
                </form>
                `
            break;
            case 2:
                attendanceform= `<button type='button' class='btn btn-success'>Checked</button>`
            break;
            case 3:
                attendanceform=`
                <form id='salarydeduction-form-${id}'>
                <input type='hidden' name='user_id' value='${id}' />
                 <input type="hidden" name="csrf_token" value='${getCsrfToken()}'>
                 <input type="number" class='col-sm-3' name="amount" id='deductions' value="0" step="0.01" min="0">
                <button  class='btn btn-danger'>Deduct <span id='deductvalue'>0</span> From Salary</button>
                </form>
                `
            break;
            default:
                attendanceform='N/A'
        }
        return attendanceform;
}


const renderAttendancePaginator=(totalpages,currentpage)=>{
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

recordsPerPageAttendance.addEventListener("change",(e)=>{
    recordsPerPageforAttendance=e.target.value;
    currentPage=1;
    (async ()=>{
        const attendanceData= await fetchData('../../api/attendance/process.fetchtodayattendance.php',currentPage,recordsPerPageforAttendance,searchQuery,null,searchdate)
        if(attendanceData){
           
            renderattendanceTable(attendanceData.attendances)
            renderAttendancePaginator(attendanceData.pagination.total_pages,attendanceData.pagination.current_page) }
       })()
    
    })


searchAttendanceForm.addEventListener("submit",(e)=>{
    e.preventDefault()
    searchQuery= new FormData(searchAttendanceForm).get('search');
    searchdate= new FormData(searchAttendanceForm).get('searchdate');
    currentPage=1;
    let recordsPerPageforAttendance=recordsPerPageAttendance.value;
    (async ()=>{
        const attendanceData= await fetchData('../../api/attendance/process.fetchtodayattendance.php',currentPage,recordsPerPageforAttendance,searchQuery,null,searchdate)
        if(attendanceData?.attendances){
           
            renderattendanceTable(attendanceData.attendances)
            renderAttendancePaginator(attendanceData.pagination.total_pages,attendanceData.pagination.current_page) 
        }else{
            attendanceTable.innerHTML+=`<tr>Oops no records found</tr>`
        }
       })()
    
})

paginator.addEventListener("click",(e)=>{
    const page=e.target.getAttribute("data-page")
    let recordsPerPageforAttendance=recordsPerPageAttendance.value;
    if(page){
        currentPage=parseInt(page);
        (async ()=>{
            const attendanceData= await fetchData('../../api/attendance/process.fetchtodayattendance.php',currentPage,recordsPerPageforAttendance,searchQuery,null,searchdate)
            if(attendanceData && attendanceData.attendances !== null){
               
                renderattendanceTable(attendanceData.attendances)
                renderAttendancePaginator(attendanceData.pagination.total_pages,attendanceData.pagination.current_page) 
            }else{
                attendanceTable.innerHTML+=`<tr>Oops no records found</tr>`
            }

           })()
        }        
})
  
   

})