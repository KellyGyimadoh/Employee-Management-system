
import fetchData from "./fetchData.js";
document.addEventListener("DOMContentLoaded", () => {

    const recordsPerPageSelect = document.getElementById("recordsPerPage");
    const searchForm = document.querySelector("#searchuser");
    const userTableBody = document.getElementById("userTableBody");
    const paginationContainer = document.querySelector(".pagination");
   


    
    let currentPage = 1;
    let recordsPerPage = recordsPerPageSelect.value;
    let searchQuery = "";

   
    


    // Fetch and render data
   (async ()=>{
    const userData= await fetchData('../../api/userview/process.viewadmins.php',currentPage,recordsPerPage,searchQuery)
    if(userData){
         renderTable(userData.users)
         renderPagination(userData.pagination.total_pages,userData.pagination.current_page);
    }
   })();
   
    // Render table content
    const renderTable =  (users) => {
        userTableBody.innerHTML = users
            .map(
                (user, index) => `
            <tr>
                <td>${(currentPage - 1) * recordsPerPage + index + 1}</td>
                <td>${user.firstname} ${user.lastname}</td>
                <td>${user.user_departments}</td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td>${user.total_salary? user.total_salary : "Not Set"}</td>
                <td>${user.status === 1
                        ? `<button class="btn btn-success btn-sm">Active</button>`
                        : `<button class="btn btn-danger btn-sm">Suspended</button>`
                    }</td>
                <td>
                    <a class="btn btn-primary btn-sm" href="../../api/userview/process.selectuser.php?userid=${user.userID}">Edit</a>
                   
                </td>
            </tr>
        `
            )
            .join("");
    };


   
    // Render pagination
    const renderPagination =   (totalPages, currentPage) => {
        paginationContainer.innerHTML = "";

        // Previous button
        paginationContainer.innerHTML += `
            <li class="page-item ${currentPage === 1 ? "disabled" : ""}">
                <a class="page-link" href="#" data-page="${currentPage - 1
            }">«</a>
            </li>
        `;

        // Page links
        for (let i = 1; i <= totalPages; i++) {
            paginationContainer.innerHTML += `
                <li class="page-item ${currentPage === i ? "active" : ""}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }

        // Next button
        paginationContainer.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? "disabled" : ""
            }">
                <a class="page-link" href="#" data-page="${currentPage + 1
            }">»</a>
            </li>
        `;
    };

    // Handle records per page change
    recordsPerPageSelect.addEventListener("change", (e) => {
        recordsPerPage = e.target.value;
        currentPage = 1; // Reset to first page
        (async ()=>{
            const userData= await fetchData('../../api/userview/process.viewadmins.php',currentPage,recordsPerPage,null)
            if(userData){
                 renderTable(userData.users)
                 renderPagination(userData.pagination.total_pages,userData.pagination.current_page);
            }
           })();
          });

    // Handle search form submission
    
    searchForm.addEventListener("submit",  (e) => {
        e.preventDefault();
        searchQuery = new FormData(searchForm).get("search");
        currentPage = 1; // Reset to first page
        let recordsPerPage = recordsPerPageSelect.value;
        (async ()=>{
            const userData= await fetchData('../../api/userview/process.viewadmins.php',currentPage,recordsPerPage,searchQuery)
            if(userData){
                 renderTable(userData.users)
                 renderPagination(userData.pagination.total_pages,userData.pagination.current_page);
            }
           })(); 
        });


    // Handle pagination clicks
    paginationContainer.addEventListener("click", (e) => {
        e.preventDefault();

        const page = e.target.getAttribute("data-page");
        let recordsPerPage = recordsPerPageSelect.value;
        if (page) {
            currentPage = parseInt(page);
            (async ()=>{
                const userData= await fetchData('../../api/userview/process.viewadmins.php',currentPage,recordsPerPage,null)
                if(userData){
                     renderTable(userData.users)
                     renderPagination(userData.pagination.total_pages,userData.pagination.current_page);
                }
               })();   }
    });

    
 

})