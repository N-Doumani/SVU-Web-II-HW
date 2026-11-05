# Student Registration System (SRS)
> **Course:** BWP501 · Web Programming II  
> **Institution:** Syrian Virtual University (SVU)  
> **Instructor:** Dr. Bassel Al-Khatib  
> **Class:** C6  

---

## 📌 Project Overview
The **Student Registration System (SRS)** is a complete, full-stack dynamic web application developed for the **BWP501** course project at SVU. It offers a secure, production-ready, and fully responsive platform to register users, manage student records (CRUD), upload profile pictures, search and filter entries dynamically, and execute real-time AJAX validations.

---

## ✨ Key Features & Technical Highlights

### 🔒 1. Authentication & Security
- **Secure Password Hashing:** User passwords are encrypted using `password_hash()` with `PASSWORD_DEFAULT` (Bcrypt).
- **Session Protection:** All core application pages (`add_student.php`, `view_students.php`, `update_student.php`) enforce active session checks (`session_start()`). Unauthenticated requests are automatically redirected to `login.php`.
- **XSS Prevention:** All user inputs and database outputs rendered in HTML are sanitized through a global `e()` helper function utilizing `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- **SQL Injection Defense:** 100% of database queries utilize **PDO Prepared Statements** with parameter binding.

### 👥 2. Student CRUD Operations & Media Management
- **Create & Update:** Forms support full student details (Full Name, Email, Phone, Address, Profile Picture).
- **Profile Picture Handling:** Optional image file uploads validated against allowed extensions (`jpg`, `jpeg`, `png`, `gif`, `webp`) and size limits. Files are processed via `move_uploaded_file()` into the `uploads/` directory.
- **Automatic Cleanup:** Updating or deleting a student record automatically removes any old image file from the server via `unlink()`.

### ⚡ 3. Real-Time AJAX Validation
- **Asynchronous Email Check:** Form fields integrate with an asynchronous backend endpoint (`check_email.php`) using the JS Fetch API to check email uniqueness in real-time without reloading the page.
- **Client-Side Form Validation:** Dynamic feedback (`.is-invalid` / `.was-validated`), instant password matching checks on registration, button loading spinners, and toast notifications handled via `assets/js/ui.js` and `assets/js/main.js`.

### 🔍 4. Search, Filtering & Pagination
- **Multi-Field SQL Search:** Case-insensitive search queries using SQL `LIKE` conditions across student name, email, and phone number.
- **Flexible Sorting:** Data ordering options (`newest`, `oldest`, `name_asc`, `name_desc`).
- **Server-Side Pagination:** Configured for **10 records per page** (`LIMIT 10 OFFSET ...`) with dynamic Previous/Next pagination links and total record counters.

### 🎨 5. Responsive Design System & Accessibility
- **Bootstrap 5.3 Integration:** Built on Bootstrap 5.3 grid and components with custom design tokens mapped to CSS variables in `:root` (`assets/css/style.css`).
- **Typography:** Custom **Inter** font with `clamp()` responsive font scaling.
- **Mobile-First Responsive Layout:**
  - **Desktop (≥ 992px):** Full navigation bar, dual-column student forms, full-width data table.
  - **Tablet (768px – 991px):** Collapsible navigation menu, optimized table columns.
  - **Mobile (< 768px):** Data tables transform into stacked responsive cards via `.table-stack` CSS rules using `data-label` attributes.
- **Accessibility (a11y):** Semantic HTML5 elements (`nav`, `main`, `footer`), screen-reader skip links (`Skip to content`), explicit ARIA attributes, and keyboard focus indicators.

---

## 🗄️ Database Architecture (MySQL)

The project relies on two relational tables managed via PDO (`db.php`):

```sql
CREATE DATABASE IF NOT EXISTS student_registration_system;
USE student_registration_system;

-- User accounts for system authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student records directory
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🛠️ Technology Stack

| Layer | Technologies Used |
| :--- | :--- |
| **Backend Engine** | PHP 8+ (PDO, Native Sessions, File I/O) |
| **Database** | MySQL / MariaDB |
| **Frontend Framework** | HTML5, CSS3, JavaScript (ES6 Vanilla JS), Bootstrap 5.3, Bootstrap Icons 1.11.3 |
| **Typography & Assets** | Inter (Google Fonts), SVG Favicon |
| **Hosting Platform** | InfinityFree (Free PHP/MySQL Web Hosting) |

---

## 📂 Complete Project Structure

```text
student_registration_system/
├── assets/
│   ├── css/
│   │   └── style.css                   # Custom theme tokens & responsive overrides
│   ├── js/
│   │   ├── main.js                     # Password match & AJAX email validation
│   │   └── ui.js                       # UI helpers (toasts, alerts, button state, modals)
│   └── img/                            # Default avatars & SVG icons
├── includes/
│   ├── footer.php                      # Shared application footer & Bootstrap scripts
│   ├── header.php                      # Navigation bar & global <head> dependencies
│   ├── helpers.php                     # Utility functions (XSS escaping e(), avatar renderer)
│   └── student_form.php                # Reusable student form partial for Add & Edit
├── uploads/                            # Server directory for student profile images
├── add_student.php                     # Add new student record page
├── check_email.php                     # Asynchronous AJAX email validation API endpoint
├── db.php                              # Database connection configuration using PDO
├── delete_student.php                  # Secure student record & image deletion handler
├── index.php                           # Application landing page & team overview
├── login.php                           # User login authentication page
├── logout.php                          # Session termination & sign-out handler
├── register.php                        # User account creation page
├── student_registration_system.sql        # MySQL database schema script
├── update_student.php                  # Edit existing student record page
└── view_students.php                   # Student list page with search, sorting & pagination
```

---

## ⚙️ Installation & Deployment Guide

### Local Setup (XAMPP / WAMP)
1. Clone or copy the `student_registration_system` directory to your server directory (e.g., `C:/xampp/htdocs/`).
2. Open **phpMyAdmin** (`http://localhost/phpmyadmin/`).
3. Create a database named `student_registration_system` and import `student_registration_system.sql`.
4. Configure database credentials in `db.php`:
   ```php
   $host     = "localhost";
   $dbname   = "student_registration_system";
   $username = "root";
   $password = "";
   ```
5. Access the application at `http://localhost/student_registration_system/`.

### Live Deployment (InfinityFree)
1. Create a MySQL Database in the InfinityFree Control Panel.
2. Import `student_registration_system.sql` via phpMyAdmin (excluding `CREATE DATABASE` queries).
3. Update `db.php` with the InfinityFree MySQL hostname, database name, username, and password.
4. Upload all files into the `htdocs/` root directory.

---

## 👥 Project Development Team & Requirement Distribution

| Avatar | Student Name | Student ID / Class | Assigned Project Requirement |
| :---: | :--- | :--- | :--- |
| **M1** | **Nour aldeen aldoumani** | `nour_aldeen_aldoumani_290139` | **Req 1: Database & Authentication**<br>MySQL Database Schema, PDO Setup, Password Hashing (`password_hash`), and Session Protection. |
| **M2** | **Ziena Foutina** | `ziena_187633` | **Req 2: Responsive Frontend & UI**<br>HTML5/CSS3 Layout, Design Tokens System, Bootstrap 5 Grid, and Mobile Card Stack Layout. |
| **M3** | **Roua Alshekh Asaad** | `roua_317850` | **Req 3: AJAX & Client-Side Validation**<br>Real-time Email Check (`check_email.php`) via Fetch API, Password Matching, and Form Feedback. |
| **M4** | **Ahed Nedal Alashoush** | `ahed_338484_c6` | **Req 4: Student CRUD & File Uploads**<br>Student Records CRUD (Add, Edit, Delete) and Image Handling (`move_uploaded_file` / `unlink`). |
| **M5** | **Taim Ghanem** | `taim_301506_c6` | **Req 5: Search, Pagination & Hosting**<br>SQL `LIKE` Search Filtering, 10-Item Server Pagination, Documentation, and InfinityFree Deployment. |

---

*© 2026 Syrian Virtual University (SVU) — BWP501 Web Programming II Course Project*
