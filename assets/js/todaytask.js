
import fetchData from "./fetchData.js";
import processForm from './processForm.js';
import handleFormMessage from './handleFormMessage.js'
import getCsrfToken from "./getCsrfToken.js";
document.addEventListener("DOMContentLoaded", () => {

    const recordsPerPageTask = document.getElementById("recordsPerPage");
    const taskTable = document.getElementById("taskTableBody");
    const searchTaskForm = document.getElementById("searchTask");
    const paginator = document.querySelector(".taskpagination")


  taskTable.addEventListener("submit", async (e) => {
          if (e.target && e.target.matches("form[id^='marktask-form']")) {
              e.preventDefault();
              const form = e.target;
              const resultData = await processForm(form, '../../api/tasks/process.updateonetask.php');
              handleFormMessage(resultData);
          }
          if (e.target && e.target.matches("form[id^='salarydeduction-form']")) {
              e.preventDefault();
  
              const salaryform = e.target;
              const resultData = await processForm(salaryform, '../../api/salaries/process.salarydeduct.php');
              handleFormMessage(resultData);
          }

          if (e.target && e.target.matches("form[id^='salaryaddition-form']")) {
            e.preventDefault();

            const salaryadditionform = e.target;
            const resultData = await processForm(salaryadditionform, '../../api/salaries/process.salaryaddition.php');
            handleFormMessage(resultData);
        }
      });
      // Delegate event listener for inputs inside the dynamically created forms
      taskTable.addEventListener("input", (e) => {
          if (e.target && e.target.matches("input[id='bonus']")) {
              const AddInput = e.target;
              const addbutton = AddInput.closest("form").querySelector(".addbtn");
              addbutton.innerText = `Add ${AddInput.value} To Salary`;
          }
          if (e.target && e.target.matches("input[id='deductions']")) {
            const deductInput = e.target;
            const addbutton = deductInput.closest("form").querySelector(".deductbtn");
            addbutton.innerText = `Deduct ${deductInput.value} From Salary`;
        }
      })
  


    let recordsPerPageforTask = recordsPerPageTask.value;

    let currentPage = 1;
    let searchQuery = "";
    let searchdate = "";
    let taskstatus="";

    // Fetch and render data


    (async () => {
        const tasksData = await fetchData('../../api/tasks/process.fetchtodaytask.php',
             currentPage, recordsPerPageforTask, searchQuery, null, searchdate,taskstatus)
        if (tasksData?.tasks) {

            rendertaskTable(tasksData.tasks)
            renderTasksPaginator(tasksData.pagination.total_pages, tasksData.pagination.current_page)
        } else {
            taskTable.innerHTML += `<tr>Oops no records found</tr>`
        }
    })()



    //departments
    const rendertaskTable = (tasks) => {

        taskTable.innerHTML = tasks.map((task, index) =>
            `<tr>

            <td>${(currentPage - 1) * recordsPerPageforTask + index}</td>
            <td>${task.name}</td>
            <td>${task.description ? task.description : "N/A"}</td>
             <td>${task.department_name}</td>
            <td>${task.assigned_by_firstname}  ${task.assigned_by_lastname}</td>
            <td>${task.assigned_to_firstname}   ${task.assigned_to_lastname}</td>
            <td>${task.created_at}</td>
            <td>${task.due_date}</td>
            <td>${task.date_completed? task.date_completed : "Not Completed"}</td>
            <td>${checkStatus(task.status)}</td>
            <td>
            <a class='btn btn-primary'
             href='../api/tasks/process.edittask.php?taskid=${task.id}'>Edit</a>
            </td>
            <td>${paymentForm(task.id, task.status,task.assigned_to,task.due_date)}</td>
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
                button = ` <button type='button' class='btn btn-success'>Completed</button>`
                break;
            case 3:
                button = ` <button type='button' class='btn btn-danger'>Late</button>`
                break;
            default:
                button = 'N/A'


        }
        return button;
    }

    function paymentForm(id, status,userid=null,duedate=null) {
        let taskform;
        switch (status) {
            case 1:
                taskform = `
                <form id='marktask-form-${id}' method='post'>
                <input type='hidden' name='id' value='${id}'/>
                 <input type="hidden" name="csrf_token" value='${getCsrfToken()}'>
                 <input type="hidden" name="status" value='${status}'>
                 <input type="hidden" name="due_date" value='${duedate}'>
                <button  class='btn btn-primary'>Mark Complete</button>
                </form>
                `
                break;
            case 2:
                taskform = `
                <form id='salaryaddition-form-${id}'>
                <input type='hidden' name='user_id' value='${userid}' />
                    <input type="hidden" name="csrf_token" value='${getCsrfToken()}'>
                    <input type="number" class='col-6 m-2' name="amount" id='bonus' value="0" step="0.01" min="0">
                <button  class='btn btn-success addbtn'>Add<span id='addvalue'>0</span> To Salary</button>
                </form>
                `
                break;
            case 3:
                taskform = `
                <form id='salarydeduction-form-${id}'>
                <input type='hidden' name='user_id' value='${userid}' />
                    <input type="hidden" name="csrf_token" value='${getCsrfToken()}'>
                    <input type="number" class='col-6 m-2' name="amount" id='deductions' value="0" step="0.01" min="0">
                <button  class='btn btn-danger deductbtn'>Deduct <span id='deductvalue'>0</span> From Salary</button>
                </form>
                `
                break;
            default:
                taskform = 'N/A'
        }
        return taskform;
    }

    const renderTasksPaginator = (totalpages, currentpage) => {
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

    recordsPerPageTask.addEventListener("change", (e) => {
        recordsPerPageforTask = e.target.value
        currentPage = 1
            (async () => {
                const tasksData = await fetchData('../../api/tasks/process.fetchtodaytask.php',
                     currentPage, recordsPerPageforPayroll, searchQuery, null, searchdate,taskstatus)
                if (tasksData) {

                    rendertaskTable(tasksData.payrolls)
                    renderTasksPaginator(tasksData.pagination.total_pages, tasksData.pagination.current_page)
                }
            })()

    })


    searchTaskForm.addEventListener("submit", (e) => {
        e.preventDefault()
        searchQuery = new FormData(searchTaskForm).get('search');
        searchdate = new FormData(searchTaskForm).get('searchdate');
        taskstatus = new FormData(searchTaskForm).get('taskstatus');
        currentPage = 1;
        let recordsPerPageforTask = recordsPerPageTask.value;
        (async () => {
            const tasksData = await fetchData('../../api/tasks/process.fetchtodaytask.php', 
                currentPage, recordsPerPageforTask, searchQuery, null, searchdate,taskstatus)
            if (tasksData?.tasks) {

                rendertaskTable(tasksData.tasks)
                renderTasksPaginator(tasksData.pagination.total_pages, tasksData.pagination.current_page)
            } else {
                taskTable.innerHTML += `<tr>Oops no records found</tr>`
            }
        })()

    })

    paginator.addEventListener("click", (e) => {
        const page = e.target.getAttribute("data-page")
        let recordsPerPageforTask = recordsPerPageTask.value;
        if (page) {
            currentPage = parseInt(page);
            (async () => {
                const tasksData = await fetchData('../../api/tasks/process.fetchtodaytask.php',
                     currentPage, recordsPerPageforTask, searchQuery, null, searchdate,taskstatus)
                if (tasksData && tasksData.tasks !== null) {

                    rendertaskTable(tasksData.tasks)
                    renderTasksPaginator(tasksData.pagination.total_pages, tasksData.pagination.current_page)
                } else {
                    taskTable.innerHTML += `<tr>Oops no records found</tr>`
                }

            })()
        }
    })



})