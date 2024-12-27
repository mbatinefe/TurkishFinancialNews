# Vulnerable Website Project

This project demonstrates a variety of common web application vulnerabilities. It includes examples of unpatched and patched code for educational purposes. The aim is to showcase potential exploits and how to mitigate them effectively.

## Table of Contents
1. [Project Overview](#project-overview)
2. [Setup Instructions](#setup-instructions)
3. [Vulnerabilities Addressed](#vulnerabilities-addressed)
4. [Database Configuration](#database-configuration)
5. [References](#references)

---

## Project Overview

This project simulates a Turkish financial news website with several intentional vulnerabilities:
- **Stored XSS**
- **SQL Injection**
- **Incorrect Authorization (CWE-863)**
- **Server-Side Request Forgery (SSRF)**
- **Unrestricted File Upload (CWE-434)**
- **Path Traversal (CWE-35)**

---

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

1. **Stored Cross-Site Scripting (XSS)**
   - **Category:** Injection (OWASP Top 10 - A03:2021)
   - **File:** `news.php`
   - **Exploit:** Malicious JavaScript injected in user comments.

2. **SQL Injection 1**
   - **Category:** Injection (OWASP Top 10 - A03:2021)
   - **Files:** `subscribe.php`
   - **Exploit:** Unsanitized inputs allow malicious SQL queries.

3. **SQL Injection 2**
   - **Category:** Injection (OWASP Top 10 - A03:2021)
   - **Files:** `login.php`
   - **Exploit:** Unsanitized inputs allow malicious SQL queries.
    
4. **Incorrect Authorization**
   - **Category:**  Broken Access Control (OWASP Top 10 - A01:2021)
   - **Files:** `navbar.php`, `admin_users.php`
   - **Exploit:** Role-based access control bypass using cookie manipulation.

6. **Server-Side Request Forgery (SSRF)**
   - **Category:** Server-Side Request Forgery (OWASP Top 10 - A10:2021)
   - **File:** `check_feeds.php`
   - **Exploit:** Unvalidated URL input allows access to internal resources.

7. **Unrestricted File Upload**
   - **Category:** Broken Access Control (OWASP Top 10 - A01:2021)
   - **File:** `signup.php`
   - **Exploit:** Upload of PHP scripts allows remote code execution.

9. **Path Traversal**
   - **Category:** Broken Access Control (OWASP Top 10 2021 - A01:2021)
   - **File:** `export_news.php`
   - **Exploit:** Directory traversal via `../` sequences in user input.

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
