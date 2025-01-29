Here’s the updated README with detailed instructions for setting up the scheduler in Windows:  

---

# Project Name: HRMS - Human Resource Management System  

## Overview  
This project is a Human Resource Management System (HRMS) built with PHP. It includes features like attendance tracking and payroll management. This README serves as a guide for setting up the project locally, configuring environment variables, and handling scheduled tasks using the Windows Task Scheduler.  

## Features  
- **Attendance Tracking:** Automatically populates the attendance table daily.  
- **Payroll Management:** Automatically populates payroll data at the end of each month.  
- **Admin Privileges:** Certain features are restricted to admin users.  

---

## Requirements  
- PHP 7.4+  
- Composer (for package management)  
- XAMPP (or a similar local server setup with MySQL)  
- Windows Task Scheduler for automated tasks  

---

## Setup Instructions  

### 1. Clone the Repository  
```bash  
git clone <repository_url>  
cd <repository_folder>  
```  

### 2. Install Dependencies  
Install the required PHP packages using Composer:  
```bash  
composer install  
```  

### 3. Configure `.env` File  
- Copy the `.env.example` file to create a new `.env` file:  
  ```bash  
  cp .env.example .env  
  ```  
- Open the `.env` file and configure the following settings:  
  ```plaintext  
  DB_CONNECTION=mysql  
  DB_HOST=127.0.0.1  
  DB_PORT=3306  
  DB_DATABASE=your_database_name  
  DB_USERNAME=root  
  DB_PASSWORD=your_password  
  ```  

> **Note:** Do not forget to update the `.env` file with your local database credentials.  

### 4. Import the Database  
If you prefer to manually create tables and columns:  
- Use the provided `database.sql` file (if available).  
- Alternatively, use the database design and schema information provided in the documentation to create the required tables and columns.  

### 5. Setup Admin Account  
1. After registering a new account through the system, open your local database in phpMyAdmin or your preferred database management tool.  
2. Run the following query to grant admin privileges to the account:  
   ```sql  
   UPDATE users SET account_type = 'admin' WHERE id = 1;  
   ```  
   Replace `1` with the ID of your registered account, if different.  

---

## Scheduled Tasks  

### Using Windows Task Scheduler  
The system includes two scripts:  
- `populate_attendance.php`: Runs daily at **12:30 AM** to populate attendance records.  
- `populate_payroll.php`: Runs at the **end of each month** to populate payroll records for the new month.  

Follow these steps to set up scheduled tasks in Windows:  

#### Step 1: Open Windows Task Scheduler  
1. Press `Win + S` and search for **Task Scheduler**.  
2. Open the Task Scheduler application.  

#### Step 2: Create a New Task  
1. In the **Task Scheduler**, click on **Create Task** in the right-hand menu.  
2. Enter a name for your task (e.g., "Populate Attendance" or "Populate Payroll").  

#### Step 3: Set the Trigger  
1. Go to the **Triggers** tab and click **New**.  
2. For `populate_attendance.php`:  
   - Choose **Daily** and set the time to **12:30 AM**.  
3. For `populate_payroll.php`:  
   - Choose **Monthly** and configure it to run on the **last day of each month**.  

#### Step 4: Set the Action  
1. Go to the **Actions** tab and click **New**.  
2. Set **Action** to **Start a Program**.  
3. In the **Program/script** field, enter the path to your PHP executable (e.g., `C:\xampp\php\php.exe`).  
4. In the **Add arguments** field, enter the path to the script you want to run:  
   - For `populate_attendance.php`:  
     ```  
     C:\path_to_project\scripts\populate_attendance.php  
     ```  
   - For `populate_payroll.php`:  
     ```  
     C:\path_to_project\scripts\populate_payroll.php  
     ```  

#### Step 5: Save and Test  
1. Click **OK** to save the task.  
2. Right-click the task and select **Run** to test it manually.  

### Alternative Manual Trigger  
For users unable to set up a task scheduler, you can create buttons in the admin dashboard to manually trigger the attendance and payroll scripts.  

#### Example Button Workflow:  
- **Attendance Button:** Executes `populate_attendance.php` when clicked.  
- **Payroll Button:** Executes `populate_payroll.php` for the current month when clicked.  

You can use PHP `exec()` or similar functions to trigger the scripts programmatically.  

---

## Additional Notes  

- Keep your `.env` file private and **do not commit it to GitHub**. Use `.env.example` for public repositories.  
- Use appropriate access control to prevent unauthorized users from accessing admin features.  

---

## Future Improvements  

- Write SQL scripts to automatically create tables and populate sample data for easier setup.  
- Add buttons for triggering attendance and payroll population as an alternative to cron jobs.  
- Improve documentation to include table structure and additional setup details.  

