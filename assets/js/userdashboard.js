
import fetchData from "./fetchData.js";
import processForm from './processForm.js';
import handleFormMessage from './handleFormMessage.js'
import getCsrfToken from "./getCsrfToken.js";
import getUserId from "./getUserId.js";
document.addEventListener("DOMContentLoaded", () => {

    const userId = getUserId()
    const totalUserTask = document.querySelector(".tasktotalnumber")
    const pendingUserTask = document.querySelector(".pendingtask")
    const completedUserTask = document.querySelector(".completedtask")
    const lateUserTask = document.querySelector(".latetask")

    const totalWorkTask = document.querySelector(".totalworktasks")
    const totalWorkPendingTask = document.querySelector(".totalpendingtask")
    const totalWorkCompletedTask = document.querySelector(".totalcompletedtask")
    const totalWorkLateTask = document.querySelector(".totallatetask")

    //'badge badge-success'

    //departments

    const departmentTable = document.querySelector(".departmentTableBody")
    const departmentTotal = document.querySelector(".departmenttotal")

    //attendance

    const totalAttendanceToday=document.querySelector(".attendancetotalnumber")
    const totalPresentToday=document.querySelector(".userspresent")
    const totalAbsentToday=document.querySelector(".usersabsent")
    const totalLateToday=document.querySelector(".userslate")

    //users

    const totalUsers=document.querySelector(".totalusers")
    const totalStaff=document.querySelector(".totalstaff")
    const totalAdmin=document.querySelector(".totaladmin")

    //userleave
    const totalRequest=document.querySelector(".totalrequestnumber");
    const pendingRequest=document.querySelector(".pendingrequest");
    const approvedRequest=document.querySelector(".approvedrequest");
    const rejectedRequest=document.querySelector(".rejectedrequest");
     
 
    // Fetch and render data
    const loadTasks = async () => {
        const tasksData = await fetchData('../../api/dashboard/process.fetchusertask.php',
            null, null, null, userId, null, null)

        if (tasksData?.tasks) {
            totalUserTask.innerHTML = tasksData.total_user_tasks
            pendingUserTask.innerHTML = tasksData.taskstatuscount['pendingTotal']
            completedUserTask.innerHTML = tasksData.taskstatuscount['completedTotal']
            lateUserTask.innerHTML = tasksData.taskstatuscount['lateTotal']
            //all tasks
            totalWorkTask.innerHTML = tasksData.total_all_tasks;
            totalWorkPendingTask.innerHTML = tasksData.total_task_status_count['pendingTotal']
            totalWorkCompletedTask.innerHTML = tasksData.total_task_status_count['completedTotal']
            totalWorkLateTask.innerHTML = tasksData.total_task_status_count['lateTotal']

        } else {
            totalUserTask.innerHTML = 'loading..'
            pendingUserTask.innerHTML = 'loading..'
            completedUserTask.innerHTML = 'loading..'
            lateUserTask.innerHTML = 'loading..'

            totalWorkTask.innerHTML = 'loading..';
            totalWorkPendingTask.innerHTML = 'loading..'
            totalWorkCompletedTask.innerHTML = 'loading..'
            totalWorkLateTask.innerHTML = 'loading..'
        }
    }

    const loadAttendance = async () => {
        const attendanceData = await fetchData('../../api/dashboard/process.fetchattendance.php',
            null, null, null, userId, null, null)

        if (attendanceData?.attendances) {
           totalAttendanceToday.innerHTML=attendanceData.total_today
           totalPresentToday.innerHTML=attendanceData.attendances.total_present
           totalAbsentToday.innerHTML=attendanceData.attendances.total_absent
           totalLateToday.innerHTML=attendanceData.attendances.total_late

        } else {
            totalAttendanceToday.innerHTML='loading..'
            totalPresentToday.innerHTML='loading..'
            totalAbsentToday.innerHTML='loading..'
            totalLateToday.innerHTML='loading..'
        }
    }

    const loadUserData = async () => {
        const userData = await fetchData('../../api/dashboard/process.fetchusers.php',
            null, null, null, userId, null, null)

        if (userData?.users) {
           totalUsers.innerHTML=userData.total_users
           totalStaff.innerHTML=userData.users['staff_total']
           totalAdmin.innerHTML=userData.users['admin_total']
          
          

        } else {
            totalUsers.innerHTML='loading..'
            totalStaff.innerHTML='loading..'
            totalAdmin.innerHTML='loading..'
            
        }
    }



    (async () => {
        

         const departmentData = await fetchData('../../api/dashboard/process.fetchdepartments.php',
            null, null, null, userId, null, null)
        if (departmentData?.departments) {
            renderDepartmentTable(departmentData?.departments)
             departmentTotal.innerHTML=`${departmentData.departments_total}`
         }

        
    })()

  const loadMyLeaveRequest=async()=>{
        const userLeaveData = await fetchData('../../api/dashboard/process.fetchoneleave.php', null,
            null, null, userId, null)
        if (userLeaveData) {


            totalRequest.innerHTML = userLeaveData.total_request;
            pendingRequest.innerHTML = userLeaveData.total_pending;
            rejectedRequest.innerHTML = userLeaveData.total_rejected
            approvedRequest.innerHTML = userLeaveData.total_approved
        } else {
           
            totalRequest.innerHTML = '...'
            pendingRequest.innerHTML = '...'
            rejectedRequest.innerHTML = '...'
            approvedRequest.innerHTML = '...'
        }
    }

   

    const renderDepartmentTable = (departments) => {
       
        departmentTable.innerHTML = 
            `<tr>
                <td><span class='badge badge-info'>${departments.departments_total}</span></td>
                <td><span class='badge badge-warning'>${departments.has_head_total}</span></td>
                <td> <span class='badge badge-success'>${departments.active_total}</span></td>
                <td> <span class='badge badge-danger'>${departments.inactive_total}</span></td>
                </tr>
                `
    }

    loadTasks()
    loadAttendance()
    loadUserData()
    loadMyLeaveRequest()


})










