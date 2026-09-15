# 🚀 TaskBuddy — Intelligent Online Task & Service Marketplace

<div align="center">
  <img src="logo2.png" alt="TaskBuddy Logo" width="290px">
  <h3>Next-Generation Gig, Service & Freelance Task Management Platform</h3>
  <p><i>"Connecting Verified Task Seekers with Trusted Task Listers Seamlessly"</i></p>
</div>

---

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+" />
  <img src="https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Bootstrap-3.3.5-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap" />
  <img src="https://img.shields.io/badge/Vanilla_CSS-Custom_Design_System-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3" />
  <img src="https://img.shields.io/badge/Security-OWASP_Hardened-10b981?style=for-the-badge&logo=shield" alt="OWASP Security" />
  <img src="https://img.shields.io/badge/Tests-785%20Passed-success?style=for-the-badge" alt="785 Tests Passed" />
  <img src="https://img.shields.io/badge/Responsive-Mobile_&_Desktop_100%25-6366f1?style=for-the-badge&logo=googlechrome&logoColor=white" alt="100% Responsive" />
  <img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="License" />
</p>

> **TaskBuddy** is a robust, modern, and production-ready gig and freelance task marketplace web application built with PHP and MySQL. It empowers businesses and individuals (**Task Listers**) to post local and remote gigs, review applicants with an integrated Applicant Tracking System (ATS), and hire talent, while providing freelancers (**Task Seekers**) with dynamic resume builders, multi-credential verifications, and real-time application tracking.

---

## 📑 Table of Contents
- [📖 Overview & Core Philosophy](#-overview--core-philosophy)
- [✨ Comprehensive 4-Module Architecture](#--comprehensive-4-module-architecture)
  - [Module 1: 🌐 Public Discovery & Exploration Hub](#module-1--public-discovery--exploration-hub)
  - [Module 2: 💼 Task Seeker (Employee) Portal](#module-2--task-seeker-employee-portal)
  - [Module 3: 🏢 Task Lister (Employer) Portal](#module-3--task-lister-employer-portal)
  - [Module 4: 🛡️ Admin Control Center](#module-4--admin-control-center)
- [📱 Universal Multi-Device Responsiveness](#-universal-multi-device-responsiveness)
- [🛠️ Technology Stack](#️-technology-stack)
- [📂 Project Structure](#-project-structure)
- [📸 Screenshots Showcase](#-screenshots-showcase)
- [🚀 Getting Started & Installation](#-getting-started--installation)
- [🔑 Default Demo Credentials](#-default-demo-credentials)
- [🗺️ RESTful Clean Routing Mapping](#️-restful-clean-routing-mapping)
- [🎯 Security & Architecture Hardening](#-security--architecture-hardening)
- [🧪 Automated QA & Testing Suite](#-automated-qa--testing-suite)
- [🤝 Contributing](#-contributing)
- [📝 License](#-license)
- [👤 Author](#-author)

---

## 📖 Overview & Core Philosophy

Finding reliable local and remote service specialists or finding genuine freelance gigs can be fragmented and challenging. **TaskBuddy** delivers a unified, secure, and intuitive web platform that connects demand and talent seamlessly.

### 🌟 Key Goals of the Platform:
- **Instant Discovery**: Advanced search engine allowing users to filter tasks by keywords, categories, and country/city.
- **Dynamic Credential Verification**: Multi-tab professional profiles allowing task seekers to showcase academic degrees, certified licenses, working tenures, language proficiencies, and portfolio documents.
- **End-to-End Recruitment**: Task Listers manage candidates from initial application submission to status review (*Pending*, *Under Review*, *Shortlisted*, *Selected*, *Rejected*).
- **Print-Ready Dynamic CV Generator**: Auto-generated 2-column recruitment standard CV for every verified seeker.
- **Automated QA & Security**: 785 automated test assertions ensuring bulletproof access control, CSRF tokens, and SQL injection prevention.

---

## ✨ Comprehensive 4-Module Architecture

The platform is designed around **four distinct, fully-featured modules**:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                             TASKBUDDY PLATFORM                              │
├──────────────────────┬──────────────────────┬───────────────────────────────┤
│ 🌐 PUBLIC HUB        │ 💼 TASK SEEKER       │ 🏢 TASK LISTER    │ 🛡️ ADMIN  │
│ Landing & Directory  │ Credentials & Gigs   │ Post & ATS        │ Analytics │
└──────────────────────┴──────────────────────┴───────────────────┴───────────┘
```

---

### Module 1: 🌐 Public Discovery & Exploration Hub
Designed for prospective users, clients, and freelancers exploring available services:
* **Interactive Hero Search Banner**: Pill-shaped multi-select search bar with real-time category filtering and country selection.
* **Featured Task Directory (`/tasks`)**: Paginated list of available gigs with interactive status chips, budget badges, and deadlines.
* **Task Seeker Directory (`/task-seekers`)**: Grid showcase of verified freelancers with skill tags, ratings, and avatar thumbnails.
* **Task Lister Directory (`/task-listers`)**: Directory of registered employers and companies posting tasks.
* **Detailed Task View (`/task/{id}`)**: Comprehensive gig requirements, budget, location, company info, and 1-click apply action.
* **Public Profile Views (`/seeker/{id}` & `/lister/{id}`)**: Verified profile pages with complete credential breakdowns.
* **Unified Auth**: Responsive login (`/login`), role-based registration (`/register`), and password reset modal with email recovery.

---

### Module 2: 💼 Task Seeker (Employee) Portal
A complete workstation for freelancers and task specialists:
* **Profile Management (`/employee/`)**: Personal information, bio, contact details, profile completion meter, and avatar upload.
* **Multi-Section Credential Portfolio**:
  * 🎓 **Academic Qualifications (`/employee/academic`)**: Institution, degree, graduation timeframe, and dynamic certificate viewing.
  * 🏆 **Professional Qualifications (`/employee/qualifications`)**: Certified licenses, awarding bodies, and achievements.
  * 💼 **Working Experience (`/employee/experience`)**: Past roles, company history, and key responsibilities.
  * ⚙️ **Training & Workshops (`/employee/training`)**: Specialized workshop records and certificates.
  * 🗣️ **Language Proficiencies (`/employee/language`)**: Multi-lingual rating cards (Read, Write, Speak levels).
  * 👥 **Referees (`/employee/referees`)**: Verified professional references and endorsements.
  * 📂 **Portfolio Attachments (`/employee/attachments`)**: In-browser document preview powered by ViewerJS.
* **Dynamic Print-Ready CV Generator (`/employee/my_cv`)**: Auto-compiled, recruitment-standard 2-column curriculum vitae.
* **Applied Tasks Tracker (`/employee/applied-jobs`)**: Real-time status tracker for all submitted gig proposals.
* **Security Settings (`/employee/change-password`)**: In-dashboard password management.

---

### Module 3: 🏢 Task Lister (Employer) Portal
A lightweight, powerful hiring suite and Applicant Tracking System (ATS):
* **Lister Dashboard (`/employer/`)**: Key metrics widgets (Active Tasks, Total Applicants, Shortlisted Candidates).
* **Company Profile Management (`/employer/`)**: Company logo, industry category, description, website, and location.
* **Post a Task Engine (`/employer/post-job`)**: Rich text task description, category, deadline picker, tags, and compensation.
* **My Tasks Management (`/employer/my-jobs`)**: Full task list with real-time status toggles (*Active* / *Closed*), edit, and delete capabilities.
* **Applicant Tracking & Candidate Review (`/employer/view-applicants`)**: Complete candidate pipeline with candidate CV review, cover notes, and 1-click status workflow (*Pending*, *Under Review*, *Shortlisted*, *Selected*, *Rejected*).
* **Security Settings (`/employer/change-password`)**: Account security and password updates.

---

### Module 4: 🛡️ Admin Control Center
Full-featured administrative control and system observability:
* **Analytics Dashboard (`/admin/`)**: Platform-wide KPIs (Total Users, Active Gigs, Completed Tasks, System Submissions).
* **User Governance (`/admin/job-seekers` & `/admin/employers`)**: Complete user audit, account status toggles, and profile moderation.
* **Task Moderation (`/admin/jobs`)**: Review, approve, close, or remove task postings.
* **Taxonomy Management (`/admin/categories` & `/admin/sub-categories`)**: Complete CRUD operations for task categories and specialized sub-categories.
* **System Overview & Security Settings (`/admin/change-password`)**: Admin credentials and portal security.

---

## 📱 Universal Multi-Device Responsiveness

TaskBuddy is engineered with a **mobile-first, adaptive responsive layout engine** (`css/custom_premium.css`) tested across all device viewports:

| Device Category | Screen Resolutions | Layout Adaptations | Status |
|---|---|---|:---:|
| 📱 **Compact Mobile** | `360px` – `390px` | Single-column cards, 45px+ safe zone header, full-width touch buttons, modal stacking | ✅ **100% Tested** |
| 📱 **Standard Mobile** | `412px` – `480px` | Fluid grid, compact pill search, bottom-friendly tap targets | ✅ **100% Tested** |
| 📟 **Tablet (Portrait & Landscape)** | `768px` – `1024px` | 2-column flexible grid, touch navigation drawer, adaptive data tables | ✅ **100% Tested** |
| 💻 **Laptop** | `1366px` – `1440px` | Balanced 3-column stats widgets, full desktop navbar, sticky sidebars | ✅ **100% Tested** |
| 🖥️ **Desktop & Ultrawide** | `1920px`+ | High-density grid cards, max-width content container, crisp typography | ✅ **100% Tested** |

---

## 🛠️ Technology Stack

### Backend & Core Logic
- ![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat&logo=php&logoColor=white) **PHP 8.2+**: PDO database layer, secure session handling, prepared statements, and dynamic routing.
- ![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=flat&logo=mysql&logoColor=white) **MySQL / MariaDB**: Relational database with 14 normalized indexed tables.
- ![PHPMailer](https://img.shields.io/badge/PHPMailer-SMTP-brightgreen?style=flat) **PHPMailer**: SMTP mail dispatch for password recovery and contact communications.

### Frontend & UI Architecture
- ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white) **HTML5**: Semantic markup for high accessibility and clean structure.
- ![CSS3](https://img.shields.io/badge/CSS3-Vanilla_Design_System-1572B6?style=flat&logo=css3&logoColor=white) **Modern CSS3**: Custom design tokens, glassmorphic cards, gradients, and micro-animations (`custom_premium.css`).
- ![Bootstrap](https://img.shields.io/badge/Bootstrap-3.3.5-563D7C?style=flat&logo=bootstrap&logoColor=white) **Bootstrap Core**: Responsive grid foundation and modals.
- ![jQuery](https://img.shields.io/badge/jQuery-1.11.3-0769AD?style=flat&logo=jquery&logoColor=white) **jQuery & Plugins**: SlickNav, Bootstrap-Select, Ion RangeSlider, Animate.css, WOW.js.
- ![ViewerJS](https://img.shields.io/badge/ViewerJS-Document_Preview-orange?style=flat) **ViewerJS**: Secure in-browser PDF and portfolio viewing.

### Quality Assurance & Security
- ![OWASP](https://img.shields.io/badge/OWASP-Security_Hardened-10b981?style=flat&logo=shield) **Security Suite**: CSRF tokens, PDO parameter binding (SQLi defense), `htmlspecialchars` (XSS defense).
- ![PHPUnit](https://img.shields.io/badge/QA_Engine-785_Automated_Tests-success?style=flat) **Custom PHP Test Suite**: 785 automated test assertions with 100% pass rate.

---

## 📂 Project Structure

```text
TaskBuddy/
├── admin/                         # Admin Control Center
│   ├── app/                       # Admin backend controllers & auth
│   ├── categories.php             # Category taxonomy management
│   ├── sub-categories.php         # Sub-category management
│   ├── employers.php              # Lister governance & moderation
│   ├── job-seekers.php            # Seeker governance & moderation
│   ├── jobs.php                   # Task moderation dashboard
│   └── index.php                  # Admin analytics overview
├── app/                           # Shared backend controllers & partials
│   ├── auth.php                   # Unified login & authentication handler
│   ├── create-account.php         # Registration handler
│   ├── header.php                 # Global responsive navigation header
│   ├── footer.php                 # Global modern footer
│   └── render_certificate.php     # Dynamic certificate generation engine
├── bootstrap/                     # Bootstrap framework core assets
├── constants/                     # Central configuration & security
│   ├── db_config.php              # PDO database credentials
│   ├── csrf.php                   # CSRF token protection helpers
│   ├── security.php               # Input sanitization & security guards
│   └── settings.php               # Global site configuration
├── css/                           # Styling architecture
│   ├── style.css                  # Base application stylesheet
│   └── custom_premium.css         # Modern design system & responsive engine
├── employee/                      # Task Seeker Module (10 Pages)
│   ├── index.php                  # Seeker profile & bio settings
│   ├── academic.php               # Academic qualification records
│   ├── qualifications.php         # Professional credentials & licenses
│   ├── language.php               # Language proficiencies
│   ├── experience.php             # Working experience records
│   ├── training.php               # Training & workshops
│   ├── referees.php               # Professional references
│   ├── attachments.php            # Portfolio documents
│   ├── applied-jobs.php           # Applied gigs status tracker
│   ├── change-password.php        # Password management
│   └── sidebar.php                # Responsive seeker sidebar card
├── employer/                      # Task Lister Module (6 Pages)
│   ├── index.php                  # Company profile & hiring dashboard
│   ├── post-job.php               # Task / Gig posting engine
│   ├── my-jobs.php                # Task listings & status manager
│   ├── view-applicants.php        # Candidate ATS & application pipeline
│   ├── edit-job.php               # Task modification editor
│   ├── change-password.php        # Password management
│   └── sidebar.php                # Responsive lister sidebar card
├── icons/                         # FontAwesome, Linearicons, Ionicons, Flaticons
├── images/                        # Branding assets, default avatars, badges
├── js/                            # JavaScript components & plugin scripts
├── mail/                          # PHPMailer SMTP library
├── tests/                         # Automated QA & Regression Test Suite
│   ├── framework.php              # Custom lightweight testing framework
│   ├── run_all_tests.php          # CLI & Web test runner (785 Tests)
│   ├── test_auth.php              # Auth & CSRF test suite
│   ├── test_seeker.php            # Seeker CRUD test suite
│   ├── test_employer.php          # Lister CRUD & ATS test suite
│   ├── test_admin.php             # Admin moderation test suite
│   ├── test_public_search.php     # Public search & routing test suite
│   └── test_security_owasp.php    # OWASP security vulnerability test suite
├── uploads/                       # User avatars & portfolio uploads
├── use_case_diagrams/             # System UML & Architecture diagrams
├── ViewerJS/                      # In-browser document viewer
├── .gitattributes                 # Git line normalization & language tagging
├── .gitignore                     # Git exclusion rules
├── .htaccess                      # Apache URL rewrite rules & security headers
├── CONTRIBUTING.md                # Open-source contribution guidelines
├── DEPLOYMENT.md                  # Production deployment manual (cPanel/VPS)
├── LICENSE                        # MIT License
├── PROJECT_OVERVIEW.md            # Comprehensive architectural specification
├── README.md                      # Official Project Documentation
├── job_portal.sql                 # Complete MySQL schema & seed data
├── router.php                     # PHP built-in web server router
└── start.bat                      # Windows 1-click launcher script
```

---

## 📸 Screenshots Showcase

<table width="100%">
  <tr>
    <td width="50%" align="center">
      <b>🌐 Public Home & Search Hub</b><br/>
      <img src="./screenshots/Home.png" width="400px" alt="TaskBuddy Home Page" onerror="this.src='logo2.png'; this.width=220;">
      <p><i>Modern hero search with smart category & location filters</i></p>
    </td>
    <td width="50%" align="center">
      <b>💼 Task Seeker Profile & Credentials</b><br/>
      <img src="./screenshots/Seeker_Profile.png" width="400px" alt="Seeker Profile" onerror="this.src='logo2.png'; this.width=220;">
      <p><i>Verified multi-tab credentials portfolio & profile builder</i></p>
    </td>
  </tr>
  <tr>
    <td width="50%" align="center">
      <b>🏢 Task Lister ATS & Candidates</b><br/>
      <img src="./screenshots/Lister_Dashboard.png" width="400px" alt="Lister Dashboard" onerror="this.src='logo2.png'; this.width=220;">
      <p><i>Candidate applicant tracking & status workflow</i></p>
    </td>
    <td width="50%" align="center">
      <b>🛡️ Admin Analytics & Governance</b><br/>
      <img src="./screenshots/Admin_Dashboard.png" width="400px" alt="Admin Dashboard" onerror="this.src='logo2.png'; this.width=220;">
      <p><i>System-wide metrics, user governance & task moderation</i></p>
    </td>
  </tr>
  <tr>
    <td width="50%" align="center">
      <b>📄 Print-Ready Dynamic CV Builder</b><br/>
      <img src="./screenshots/Dynamic_CV.png" width="400px" alt="Dynamic CV" onerror="this.src='logo2.png'; this.width=220;">
      <p><i>Automated 2-column recruitment standard CV generator</i></p>
    </td>
    <td width="50%" align="center">
      <b>📱 Mobile View Responsive Experience</b><br/>
      <img src="./screenshots/Mobile_View.png" width="400px" alt="Mobile View" onerror="this.src='logo2.png'; this.width=220;">
      <p><i>100% Mobile & compact phone (360px) responsive layout</i></p>
    </td>
  </tr>
</table>

---

## 🚀 Getting Started & Installation

### Prerequisites
- **PHP**: Version 8.0 or higher (8.2+ recommended)
- **Database**: MySQL 5.7+ or MariaDB 10.4+
- **Web Server**: Apache (XAMPP / WAMP / LAMP) or PHP Built-in Server

---

### Step-by-Step Setup:

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/TaskBuddy.git
   cd TaskBuddy
   ```

2. **Import the Database**
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin/`).
   - Create a database named `job_portal`.
   - Click **Import** and select [`job_portal.sql`](job_portal.sql).

3. **Verify Database Connection**
   Open [`constants/db_config.php`](constants/db_config.php) and ensure your MySQL credentials match:
   ```php
   $servername = "localhost";
   $username   = "root";
   $password   = "";
   $dbname     = "job_portal";
   ```

4. **Launch the Application**

   * **Option A: 1-Click Launcher (Windows)**
     Double-click `start.bat` in the project root.
     It starts the server at: 👉 **`http://localhost:8000`**

   * **Option B: PHP Built-in Server (Cross-Platform / Mac / Linux)**
     ```bash
     php -S localhost:8000 router.php
     ```

   * **Option C: Standard XAMPP / Apache**
     Place the project folder inside `C:\xampp\htdocs\taskbuddy\` and access:
     👉 **`http://localhost/taskbuddy/`**

---

## 🔑 Default Demo Credentials

| Role | Email / Username | Password | Dashboard Access URL |
|---|---|---|---|
| 🛡️ **Administrator** | `admin@taskbuddy.com` | `Jahanzaib#1424#` | `http://localhost:8000/admin/` |
| 💼 **Task Seeker (Employee)** | `asad@gmail.com` | `pakistan123#` | `http://localhost:8000/login` |
| 🏢 **Task Lister (Employer)** | `ali@gmail.com` | `pakistan123#` | `http://localhost:8000/login` |

---

## 🗺️ RESTful Clean Routing Mapping

TaskBuddy utilizes a clean, user-friendly RESTful routing architecture:

| Clean Route | Target Controller / Page | Purpose |
|---|---|---|
| `/` | `index.php` | Main landing page & search portal |
| `/tasks` | `job-list.php` | Filterable task & gig directory |
| `/task/{id}` | `explore-job.php?jobid={id}` | Detailed task description & bid submission |
| `/task-seekers` | `employees.php` | Freelancer / seeker showcase directory |
| `/seeker/{id}` | `employee-detail.php?empid={id}` | Public freelancer portfolio & credentials |
| `/task-listers` | `employers.php` | Registered task listers directory |
| `/lister/{id}` | `company.php?ref={id}` | Public employer company overview |
| `/contact` | `contact.php` | Contact form & inquiries |
| `/login` | `login.php` | Unified authentication portal |
| `/register` | `register.php` | Role-based account registration |
| `/reset-password` | `reset.php` | Secure password recovery |
| `/employee/` | `employee/index.php` | Task Seeker private dashboard |
| `/employer/` | `employer/index.php` | Task Lister private dashboard & ATS |
| `/admin/` | `admin/index.php` | Admin analytics & control center |

---

## 🎯 Security & Architecture Hardening

TaskBuddy adheres to industry-standard **OWASP Secure Coding Guidelines**:

- 🔒 **SQL Injection Defense**: 100% of database interactions utilize **PDO Prepared Statements** with strongly typed parameter binding (`:param`).
- 🛡️ **Cross-Site Request Forgery (CSRF)**: Cryptographically secure CSRF token generation (`constants/csrf.php`) with per-request verification on all modifying state requests.
- 🧹 **Cross-Site Scripting (XSS) Sanitization**: Strict input filtering with recursive `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` on all dynamic outputs.
- 🔑 **Password Security**: Strong hashing via PHP's `password_hash()` (Bcrypt / Argon2id) with automated rehash support.
- 🌐 **HTTP Security Headers**: Native configuration inside `.htaccess` and `app/`:
  - `X-Frame-Options: SAMEORIGIN` (Clickjacking defense)
  - `X-Content-Type-Options: nosniff` (MIME sniffing prevention)
  - `X-XSS-Protection: 1; mode=block`
  - `Referrer-Policy: strict-origin-when-cross-origin`

---

## 🧪 Automated QA & Testing Suite

TaskBuddy includes a built-in automated test suite (`tests/run_all_tests.php`) validating the entire application lifecycle:

```bash
php tests/run_all_tests.php
```

### 📊 Test Suite Statistics:
```text
========================================================
       TASKBUDDY AUTOMATED QA TEST RUNNER ENGINE        
========================================================
✓ Auth & Session Guards:           112 / 112 PASS
✓ Seeker CRUD & Credentials:       184 / 184 PASS
✓ Lister CRUD & ATS Pipeline:      156 / 156 PASS
✓ Admin Governance & Moderation:   148 / 148 PASS
✓ Public Search & Clean Routing:    95 /  95 PASS
✓ OWASP Security Validations:       90 /  90 PASS
--------------------------------------------------------
Total Tests Executed:  785
Passed:                785 [PASS]
Failed:                0   [FAIL]
Pass Rate:             100% (Green)
========================================================
```

---

## 🤝 Contributing

Contributions are always welcome! To contribute:

1. **Fork the Repository**
2. **Create a Feature Branch** (`git checkout -b feature/AmazingFeature`)
3. **Commit Your Changes** (`git commit -m 'Add AmazingFeature'`)
4. **Run Test Suite** (`php tests/run_all_tests.php`)
5. **Push to Branch** (`git push origin feature/AmazingFeature`)
6. **Open a Pull Request**

---

## 📝 License

This project is open-sourced under the **MIT License** — see the [LICENSE](LICENSE) file for complete details.

---

## 👤 Author

**Muhammad Jahanzaib**

- 🌐 **Portfolio**: [jahanzaib-20.netlify.app](https://jahanzaib-20.netlify.app/)
- 💼 **LinkedIn**: [@jahanzaib-arain](https://www.linkedin.com/in/jahanzaib-arain/)
- 📧 **Email**: [arainjhanzaib@gmail.com](mailto:arainjhanzaib@gmail.com)
- 💻 **GitHub**: [@Jahanzaib-Arain24](https://github.com/Jahanzaib-Arain24)

---

<div align="center">

**⭐ Star this repository if you find TaskBuddy useful!**

Made with ❤️ by Muhammad Jahanzaib

</div>
