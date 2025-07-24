watch live on :---  https://pindwaracourt.my-style.in/

# 📌 MyHearingDate

**MyHearingDate** is a secure, easy-to-use web portal for advocates and clients to manage and track court case hearing dates online.

---

## 🚀 Features

- 🏛️ **Advocate Registration & Login**  
  Secure sign-up with developer PIN & unique mobile number.

- 📅 **Add, Edit & Delete Cases**  
  Advocates can maintain up-to-date case logs with next hearing dates.

- 🔍 **Client Case Status Checker**  
  Clients check their case by selecting their advocate & entering a case number or partial case name (fuzzy search).

- 🔒 **Multi-Advocate Support**  
  Each advocate’s data stays separate and secure — clients only see their advocate’s cases.

- 💻 **Mobile & Desktop Friendly**  
  Fully responsive design using Bootstrap 5.

- 🎨 **Clean Dashboard UI**  
  Professional look with background image & modern cards.

---

## ⚙️ Built With

- PHP
- MySQL
- Bootstrap 5
- Vanilla JS (for enhanced search, optional)

---

## 🗂️ Project Structure

project/
│
├── assets/
│ └── images/
│ └── background.png
├── config.php
├── index.php
├── register.php
├── login.php
├── dashboard.php
├── edit_case.php
├── delete_case.php
└── logout.php


---

## 🔑 Developer PIN System

To prevent unauthorized sign-ups, each advocate must enter a **developer PIN** (`@Prerak45`) when registering. This allows you to monetize by selling unique access.

---

## 🗄️ Database Structure

**Tables:**

1. `advocates`  
   - `id`
   - `name`
   - `mobile` (unique key)
   - `email`
   - `password`

2. `cases`  
   - `id`
   - `advocate_id`
   - `case_number`
   - `case_name`
   - `next_hearing_date`

---
