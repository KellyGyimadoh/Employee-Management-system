
import fetchData from "./fetchData.js";
import processForm from './processForm.js';
import handleFormMessage from './handleFormMessage.js'
import getCsrfToken from "./getCsrfToken.js";
import getUserId from "./getUserId.js";
document.addEventListener("DOMContentLoaded", () => {

    const recordsPerPageUserLeave = document.getElementById("recordsPerPage");
    const userLeaveTable = document.getElementById("leaveTableBody");
    const searchUserLeaveForm = document.getElementById("searchLeave");
    const paginator = document.querySelector(".leavepagination")


  userLeaveTable.addEventListener("submit", async (e) => {
          if (e.target && e.target.matches("form[id^='markuserLeave-form']")) {
              e.preventDefault();
              const form = e.target;
              const resultData = await processForm(form, '../../api/leave/process.approverequest.php');
              handleFormMessage(resultData);
          }
         
      });
     
  


    let recordsPerPageforUserLeave = recordsPerPageUserLeave.value;

    let currentPage = 1;
    let searchQuery = "";
    let searchdate = "";
    let userLeavestatus="";
    // Fetch and render data


    (async () => {
        const userLeaveData = await fetchData('../../api/leave/process.fetchall.php', currentPage,
             recordsPerPageforUserLeave,
             searchQuery, null, searchdate,userLeavestatus)
        if (userLeaveData?.requests) {

            renderuserLeaveTable(userLeaveData.requests)
            renderUserLeavePaginator(userLeaveData.pagination.total_pages, userLeaveData.pagination.current_page)
        } else {
            userLeaveTable.innerHTML += `<tr>Oops no records found</tr>`
        }
    })()



    //departments
    const renderuserLeaveTable = (requests) => {

        userLeaveTable.innerHTML = requests.map((request, index) =>
            `<tr>

            <td>${(currentPage - 1) * recordsPerPageforUserLeave + index}</td>
            <td>${request.requested_by_firstname}  ${request.requested_by_lastname}</td>
            <td>${request.type}</td>
            <td>${request.start_date}</td>
            <td>${request.end_date}</td>
            <td>${request.approved_by ? `${request.approved_by_firstname} 
              ${request.approved_by_lastname}`: 'N/A' } </td>
              <td>${request.created_at}</td>
            <td>${checkStatus(request.status)}</td>
            <td>
            <a class='btn btn-primary'
             href='../api/leave/process.editrequest.php?leaveid=${request.id}'>Edit</a>
            </td>
            <td>${approveRequestForm(request.id,request.status,request.approved_by)}</td>
            </tr>`
        ).join("")
    }
    function checkStatus(status) {
        let button;
        switch (status) {
            case 1:
                button = `<button type='button' class='btn btn-warning'>Pending</button>`
                break;
            case 2:
                button = ` <button type='button' class='btn btn-success'>Approved</button>`
                break;
            case 3:
                button = ` <button type='button' class='btn btn-danger'>Rejected</button>`
                break;
            default:
                button = 'N/A'


        }
        return button;
    }

    function approveRequestForm(id,status=null,approvedBy=null) {
        let userLeaveForm;
        switch (status) {
            case 1:
                userLeaveForm = `
                <form id='markuserLeave-form-${id}' method='post'>
                <input type='hidden' name='id' value='${id}'/>
                 <input type="hidden" name="csrf_token" value='${getCsrfToken()}'>
                 <input type="hidden" name="approved_by" value='${getUserId()}'>
                <button  class='btn btn-primary'>Approve</button>
                </form>
                `
                break;
            case 2:
                userLeaveForm = `
                <form id='markuserLeave-form-${id}'>
                  <input type='hidden' name='id' value='${id}'/>
                <input type='hidden' name='approved_by' value='${approvedBy}' />
                    <input type="hidden" name="csrf_token" value='${getCsrfToken()}'>
                    <input type="hidden" name="status" value='${status}'>
                <button  class='btn btn-dark'>UnApprove</button>
                </form>
                `
                break;
            case 3:
                userLeaveForm = `
                <form id='markuserLeave-form-${id}' method='post'>
                <input type='hidden' name='id' value='${id}'/>
                 <input type="hidden" name="csrf_token" value='${getCsrfToken()}'>
                 <input type="hidden" name="approved_by" value='${getUserId()}'>
                <button  class='btn btn-primary'>Approve</button>
                </form>
                `
                break;
            default:
                userLeaveForm = 'N/A'
        }
        return userLeaveForm;
    }

    const renderUserLeavePaginator = (totalpages, currentpage) => {
        paginator.innerHTML = "";
        paginator.innerHTML += `
    <li class="page-item ${currentpage == 1 ? "disabled" : ""}">
        <a class="page-link" href="#" data-page=${currentPage - 1}>«</a>
    </li>
    `

        for (let i = 1; i <= totalpages; i++) {
            paginator.innerHTML += `<li class="page-item ${currentPage === i ? "active" : ""}"} >
        <a class="page-link" href="#" data-page=${i}>${i}</a>
        </li>`
        }

        paginator.innerHTML += `
    <li class="page-item ${currentpage == totalpages ? "disabled" : ""}">
        <a class="page-link" href="#" data-page=${currentPage + 1}>»</a>
    </li>`
    }

    recordsPerPageUserLeave.addEventListener("change", (e) => {
        recordsPerPageforUserLeave = e.target.value
        currentPage = 1
            (async () => {
                const userLeaveData = await fetchData('../../api/leave/process.fetchall.php',
                     currentPage, recordsPerPageforUserLeave, searchQuery, null, searchdate,userLeavestatus)
                if (userLeaveData) {

                    renderuserLeaveTable(userLeaveData.requests)
                    renderUserLeavePaginator(userLeaveData.pagination.total_pages, userLeaveData.pagination.current_page)
                }
            })()

    })


    searchUserLeaveForm.addEventListener("submit", (e) => {
        e.preventDefault()
        searchQuery = new FormData(searchUserLeaveForm).get('search');
        searchdate = new FormData(searchUserLeaveForm).get('searchdate');
        userLeavestatus = new FormData(searchUserLeaveForm).get('userLeavestatus');
        currentPage = 1;
        let recordsPerPageforUserLeave = recordsPerPageUserLeave.value;
        (async () => {
            const userLeaveData = await fetchData('../../api/leave/process.fetchall.php', currentPage,
                 recordsPerPageforUserLeave, searchQuery, null, searchdate,userLeavestatus)
            if (userLeaveData?.requests) {

                renderuserLeaveTable(userLeaveData.requests)
                renderUserLeavePaginator(userLeaveData.pagination.total_pages, userLeaveData.pagination.current_page)
            } else {
                userLeaveTable.innerHTML += `<tr>Oops no records found</tr>`
            }
        })()

    })

    paginator.addEventListener("click", (e) => {
        const page = e.target.getAttribute("data-page")
        let recordsPerPageforUserLeave = recordsPerPageUserLeave.value;
        if (page) {
            currentPage = parseInt(page);
            (async () => {
                const userLeaveData = await fetchData('../../api/leave/process.fetchall.php',
                     currentPage, recordsPerPageforUserLeave, searchQuery, null, searchdate,userLeavestatus)
                if (userLeaveData && userLeaveData.requests !== null) {

                    renderuserLeaveTable(userLeaveData.requests)
                    renderUserLeavePaginator(userLeaveData.pagination.total_pages, userLeaveData.pagination.current_page)
                } else {
                    userLeaveTable.innerHTML += `<tr>Oops no records found</tr>`
                }

            })()
        }
    })



})