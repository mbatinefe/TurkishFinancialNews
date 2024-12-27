# Turkish Financial News

This repository contains a demonstration of common web application vulnerabilities and their corresponding fixes. It is divided into two main folders: one showcasing the vulnerable implementations and another with the patched versions of the website. The purpose is to provide an educational resource for understanding, exploiting, and mitigating security vulnerabilities.

---

## Folder Structure

1. **`patched website/`**
   - Contains the secured version of the website.
   - All vulnerabilities identified in the "vulnerable website" have been addressed following OWASP best practices.
   - Includes secure coding examples, mitigations, and explanations for each fix.

2. **`vulnerable website/`**
   - Contains the original, intentionally insecure version of the website.
   - Demonstrates common vulnerabilities, including:
     - Stored Cross-Site Scripting (XSS)
     - SQL Injection
     - Incorrect Authorization (CWE-863)
     - Server-Side Request Forgery (SSRF)
     - Unrestricted File Upload (CWE-434)
     - Path Traversal (CWE-35)
   - This folder is designed for ethical hacking exercises and testing exploitations.

---

## Getting Started

### Prerequisites
- Docker and Docker Compose
- A web browser
- Basic understanding of PHP, MySQL, and web security concepts

---

## Key Features

### Vulnerable Website
- Demonstrates real-world web vulnerabilities for educational purposes.
- Includes examples of the following vulnerabilities:
  - Stored Cross-Site Scripting (XSS)
  - SQL Injection
  - Incorrect Authorization (CWE-863)
  - Server-Side Request Forgery (SSRF)
  - Unrestricted File Upload (CWE-434)
  - Path Traversal (CWE-35)
- Detailed technical explanations of each vulnerability and example exploits.

### Patched Website
- Implements secure coding techniques to mitigate the vulnerabilities showcased in the vulnerable website.
- Provides:
  - Before-and-after comparisons of the code.
  - Step-by-step explanations of each fix.
  - Adherence to OWASP best practices.

---

## References

- [OWASP Top Ten](https://owasp.org/www-project-top-ten/)
- [Cross-Site Scripting Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)
- [SQL Injection Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html)
- [Authorization Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authorization_Cheat_Sheet.html)
- [Server-Side Request Forgery Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Server_Side_Request_Forgery_Prevention_Cheat_Sheet.html)
- [File Upload Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html)
- [Path Traversal Prevention](https://owasp.org/www-community/attacks/Path_Traversal)

---

## Disclaimer

This repository is for educational purposes only. The "vulnerable website" folder contains intentionally insecure code that must be used in a controlled environment. Exploiting these vulnerabilities in unauthorized environments is strictly illegal and unethical. Always practice responsible disclosure and adhere to local laws and ethical guidelines.
