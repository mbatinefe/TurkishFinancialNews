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
   - **Mitigation:** Use `htmlspecialchars()` to sanitize output.

2. **SQL Injection 1**
   - **Category:** Injection (OWASP Top 10 - A03:2021)
   - **Files:** `subscribe.php`
   - **Exploit:** Unsanitized inputs allow malicious SQL queries.
   - **Mitigation:** Implement prepared statements and input validation.

3. **SQL Injection 2**
   - **Category:** Injection (OWASP Top 10 - A03:2021)
   - **Files:** `login.php`
   - **Exploit:** Unsanitized inputs allow malicious SQL queries.
   - **Mitigation:** Implement prepared statements and input validation.
    
4. **Incorrect Authorization**
   - **Category:**  Broken Access Control (OWASP Top 10 - A01:2021)
   - **Files:** `navbar.php`, `admin_users.php`
   - **Exploit:** Role-based access control bypass using cookie manipulation.
   - **Mitigation:** Use server-side session validation and restrict direct user access.

6. **Server-Side Request Forgery (SSRF)**
   - **Category:** Server-Side Request Forgery (OWASP Top 10 - A10:2021)
   - **File:** `check_feeds.php`
   - **Exploit:** Unvalidated URL input allows access to internal resources.
   - **Mitigation:** Validate URLs and restrict protocols to HTTP/HTTPS.

7. **Unrestricted File Upload**
   - **Category:** Broken Access Control (OWASP Top 10 - A01:2021)
   - **File:** `signup.php`
   - **Exploit:** Upload of PHP scripts allows remote code execution.
   - **Mitigation:** Validate file types and restrict uploads to images only.

8. **Path Traversal**
   - **Category:** Broken Access Control (OWASP Top 10 2021 - A01:2021)
   - **File:** `export_news.php`
   - **Exploit:** Directory traversal via `../` sequences in user input.
   - **Mitigation:** Use `realpath()` and `basename()` to sanitize file paths.
