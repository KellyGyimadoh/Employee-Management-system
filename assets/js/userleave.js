
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
    const userId=getUserId()

    //cards
    const totalRequest=document.querySelector(".totalrequestnumber");
    const pendingRequest=document.querySelector(".pendingrequest");
    const approvedRequest=document.querySelector(".approvedrequest");
    const rejectedRequest=document.querySelector(".rejectedrequest");
     
  


    let recordsPerPageforUserLeave = recordsPerPageUserLeave.value;

    let currentPage = 1;
    let searchQuery = "";
    let searchdate = "";
    let userLeavestatus="";
    // Fetch and render data


    (async () => {
        const userLeaveData = await fetchData('../../api/leave/process.fetchoneuser.php', currentPage,
             recordsPerPageforUserLeave,
             searchQuery, userId, searchdate,userLeavestatus)
        if (userLeaveData?.requests) {

            renderuserLeaveTable(userLeaveData.requests)
            renderUserLeavePaginator(userLeaveData.pagination.total_pages, userLeaveData.pagination.current_page)
            totalRequest.innerHTML=userLeaveData.total_request;
            pendingRequest.innerHTML=userLeaveData.total_pending;
            rejectedRequest.innerHTML=userLeaveData.total_rejected
            approvedRequest.innerHTML=userLeaveData.total_approved
        } else {
            userLeaveTable.innerHTML += `<tr>Oops no records found</tr>`
            totalRequest.innerHTML='...'
            pendingRequest.innerHTML='...'
            rejectedRequest.innerHTML='...'
            approvedRequest.innerHTML='...'
        }
    })()



    //departments
    const renderuserLeaveTable = (requests) => {

        userLeaveTable.innerHTML = requests.map((request, index) =>
            `<tr>

            <td>${(currentPage - 1) * recordsPerPageforUserLeave + index}</td>
            <td>${request.type}</td>
            <td>${request.start_date}</td>
            <td>${request.end_date}</td>
            <td>${request.approved_by ? `${request.approved_by_firstname} 
              ${request.approved_by_lastname}`: 'N/A' } </td>
              <td>${request.created_at}</td>
            <td>${checkStatus(request.status)}</td>
            <td>${approveRequestForm(request.id,request.status)}</td>
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
                <a class='btn btn-primary'
                href='../api/leave/process.editrequest.php?leaveid=${id}'>Edit</a>
                `
                break;
            case 2:
                userLeaveForm = `
                
                <button  class='btn btn-success'>Enjoy😁</button>
            
                `
                break;
            case 3:
                userLeaveForm = `
               
                <a href='create.php'  class='btn btn-info'>Apply New Request</button>
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
                const userLeaveData = await fetchData('../../api/leave/process.fetchoneuser.php',
                     currentPage, recordsPerPageforUserLeave, searchQuery, userId, searchdate,userLeavestatus)
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
            const userLeaveData = await fetchData('../../api/leave/process.fetchoneuser.php', currentPage,
                 recordsPerPageforUserLeave, searchQuery, userId, searchdate,userLeavestatus)
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
                const userLeaveData = await fetchData('../../api/leave/process.fetchoneuser.php',
                     currentPage, recordsPerPageforUserLeave, searchQuery, userId, searchdate,userLeavestatus)
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