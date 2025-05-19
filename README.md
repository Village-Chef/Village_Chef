# 🍽️ Village Chef – Online Food Ordering & Restaurant Management System

**Village Chef** is a PHP and MySQL-based full-stack web application designed to streamline online food ordering and restaurant administration. It offers two powerful panels: a **Customer Panel** for users to browse and order food and an **Admin Panel** for managing restaurants, users, payments, and analytics.

---

## 🚀 Features Overview

### 👨‍🍳 Customer Panel
- **User Registration** with automatic **Welcome Email** using **PHPMailer**
- **Login/Logout** system with session management
- **Search food** by:
  - Cuisine
  - Restaurant
  - Dish name
- Add items to cart and **place orders online**
- **Secure online payment** via **Razorpay**
- Track order status in real-time
- **Order history** with downloadable PDF receipts
- **Forgot Password** with **OTP-based Email Verification** (via PHPMailer)

---

### 🛠️ Admin Panel
- Secure admin login with session authentication
- **Forgot Password** with OTP email verification
- View and manage:
  - ✅ Restaurants
  - ✅ Cuisines
  - ✅ Menu Items
  - ✅ Orders and their statuses
  - ✅ Users
  - ✅ Reviews
  - ✅ Payments
- Auto-generate:
  - 📄 **Order Receipt PDFs**
  - 📄 **Payment Receipt PDFs**
  - 📄 **Monthly Payment Reports** (via **FPDF**)
- Advanced **Admin Dashboard** with real-time charts using **Chart.js**
- View **Top Customers**, **Top-Selling Items**, and **Recent Activities**
- Perform **Quick Actions** from a unified interface

---

## 📊 Admin Dashboard Highlights

| Feature | Description |
|--------|-------------|
| 📈 Revenue Trends | Line chart for daily/monthly earnings |
| 💳 Payment Methods | Pie chart displaying payment gateway usage |
| 📦 Order Status | Pie chart to monitor order progression (Pending, Accepted, Delivered, Cancelled) |
| 👥 User Registrations | Line chart of new customer signups |
| 🏆 Top Restaurants | Horizontal bar chart showing most popular restaurants |
| 🍕 Top-Selling Items | Table listing most ordered menu items |
| ⭐ Top Customers | List of users with highest total spend |
| ⚡ Quick Actions | Shortcuts to manage core tasks |
| 🕒 Recent Orders | Log of recent transactions with time & status |
| 📊 Summary Stats | Total Revenue, Orders, Active Users, Restaurants, etc. |

---

## 🧠 Tech Stack

| Layer | Technology Used |
|------|------------------|
| Frontend | HTML5, CSS3, Tailwind |
| Backend | PHP (Procedural) |
| Database | MySQL |
| Email Integration | PHPMailer |
| Online Payment | Razorpay API |
| PDF Generation | FPDF |
| Charts & Analytics | Chart.js |
| Local Server | XAMPP (Apache & MySQL) |

---

## 🔒 Security Features

- Session-based authentication (Admin & Customer)
- OTP-based password reset via email
- SQL input sanitization
- Password encryption (can be upgraded to `password_hash`)
- Proper validation and alert handling on user forms


## 🛠️ Local Setup Instructions

### ✅ Requirements
- XAMPP or any Apache + MySQL setup
- PHP 7.x or newer
- Composer (optional, for PHPMailer)
- Internet connectivity for Razorpay

### 📥 Step-by-Step Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/village-chef.git
   cd village-chef
2. Start Apache and MySQL using XAMPP
3. Import the Database
    - Open phpMyAdmin
    - Create a new database named village_chef
    - Import the SQL file from:
      ```bash
      wdos_foodies.sql
4. Configure Database Connection
    - Open db.php
    - Update credentials if needed:
      ```
      $host = "localhost";
      $user = "root";
      $pass = "";
      $db   = "wdos_foodies";
5. Place Project in htdocs Folder
6. Access Application

## 📧 Contact
- For queries, suggestions, or contributions:
  - Parthiv Shingala
  - MSc-IT | Charotar University of Science and Technology
  - 📧 parthivshingala@gmail.com | purvvirpariya14@gmail.com
  - 💼 [LinkedIn](https://www.linkedin.com/in/parthiv-shingala-933224322/)  
 
## 📝 License
- This project is for educational and academic purposes only.
- All rights reserved © 2025 Parthiv Shingala.




