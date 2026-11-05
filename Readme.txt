# Student Registration System (SRS)
> **BWP501 · Web Programming II** | **Syrian Virtual University (SVU)**  
> Supervised by: **Dr. Bassel Al-Khatib** | **Class:** C6

---

## 📌 Project Overview
The **Student Registration System (SRS)** is a full-stack dynamic web application built for the **BWP501** course project. It provides a secure, fully responsive platform to manage student records, search and filter database entries, upload profile pictures, and maintain authentication with hashed credentials.

---

## ✨ Key Features
- **Authentication & Security:** User registration, password hashing (`password_hash`), session control, and access protection across all inner pages.
- **Student CRUD Operations:** Full capability to Create, Read, Update, and Delete student records with image file management (`move_uploaded_file` & `unlink`).
- **Real-time AJAX Validation:** Asynchronous email uniqueness check via Fetch API without refreshing the page.
- **Search, Sorting & Pagination:** Dynamic SQL `LIKE` filtering across student records with a 10-item limit per page and custom sorting.
- **Responsive Modern UI:** Modern layout styled with **Bootstrap 5**, custom CSS tokens, mobile-first design, and accessibility features.

---

## 🛠️ Tech Stack
- **Backend:** PHP 8+ (PDO Database Handler, Sessions)
- **Database:** MySQL / MariaDB (Prepared Statements)
- **Frontend:** HTML5, CSS3, JavaScript (ES6 / Vanilla JS), Bootstrap 5.3, Bootstrap Icons

---

## 📂 Project Structure
```text
student_registration_system/
├── assets/
│   ├── css/
│   │   └── style.css            # Custom layout rules & design tokens
│   ├── js/
│   │   ├── main.js              # Client-side form validation & AJAX integration
│   │   └── ui.js                # UI helpers (toasts, alerts, button loading states)
│   └── img/                     # Icons & default placeholders
├── includes/
│   ├── footer.php               # Shared page footer & scripts
│   ├── header.php               # Navbar & head assets
│   ├── helpers.php              # Utility functions (XSS protection, avatars)
│   └── student_form.php         # Reusable form component for Add/Edit
├── uploads/                     # Uploaded student profile pictures
├── add_student.php              # Create student record page
├── check_email.php              # AJAX API endpoint for email validation
├── db.php                       # Database connection configuration (PDO)
├── delete_student.php           # Delete handler with file cleanup
├── index.php                    # Landing page & team overview
├── login.php                    # User authentication login page
├── logout.php                   # Session termination endpoint
├── register.php                 # User registration page
├── student_registration_system.sql # MySQL Schema & initial tables
├── update_student.php           # Edit student record page
└── view_students.php            # Student list page with search & pagination
⚙️ Installation & Local Setup
Clone or Download: Place the project folder inside your web server directory (e.g., htdocs for XAMPP).
Database Setup:
Open phpMyAdmin and select/create a database.
Import student_registration_system.sql.
Configure Database Connection: Update db.php with your local database credentials:
\$host     = "localhost";
\$dbname   = "student_registration_system";
\$username = "root";
\$password = "";
Run Application: Navigate to http://localhost/student_registration_system/ in your browser.
👥 Project Development Team
Avatar
Student Name
ID / Class
Key Responsibilities
M1
Nour aldeen aldoumani
nour_aldeen_aldoumani_290139
Req 1: Database Schema, PDO Setup & Session Auth
M2
Ziena Foutina
ziena_187633
Req 2: HTML5/CSS3 Layout & Bootstrap 5 Responsiveness
M3
Roua Alshekh Asaad
roua_317850
Req 3: Real-time AJAX Validation & Password Check
M4
Ahed Nedal Alashoush
ahed_338484_c6
Req 4: Student CRUD Operations & File Uploads
M5
Taim Ghanem
taim_301506_c6
Req 5: SQL Search, 10-Item Pagination & Web Hosting
© 2026 Syrian Virtual University (SVU) — BWP501 Course Project