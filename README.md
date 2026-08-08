# EventEase — Event Management System 🎉

EventEase is a web-based **Event Management System** developed as an MCA Major Project. It provides a centralized platform where users can explore upcoming events, view event details, book seats, manage their bookings, and manage their profiles.

The system also provides an **Admin Panel** through which administrators can manage users, events, categories, and bookings.

---

## 📌 Project Overview

EventEase is designed to simplify the process of discovering and managing events through a user-friendly web application.

The system has two major roles:

* **User**
* **Admin**

### 👤 User

Users can:

* Register an account
* Login securely
* Browse upcoming events
* View event details
* Book available seats
* View their bookings
* Cancel bookings
* View cancelled booking history
* Manage their profile
* Edit profile information
* Change password
* Logout securely

### 🔐 Admin

Administrators can:

* Login through the Admin Login
* Access the Admin Dashboard
* View dashboard statistics
* Add events
* Manage events
* Manage categories
* Manage bookings
* Manage users
* View booking and revenue information
* Logout securely

---

## ✨ Key Features

### Authentication & Authorization

* User Registration
* User Login
* Admin Login
* Secure Logout
* Session-based authentication
* Role-based authorization
* Protected User Dashboard
* Protected Admin Dashboard
* Password hashing using PHP `password_hash()`
* Password verification using `password_verify()`

### Event Management

* Dynamic event listing
* Event categories
* Event details
* Event date and time
* Event venue
* Event price
* Available seat tracking
* Event images
* Upcoming event filtering
* Sold-out event handling

### Booking Management

* Event booking
* User-specific bookings
* Booking amount tracking
* Booking date tracking
* Booking status
* Booking cancellation
* Cancelled bookings excluded from active booking count
* Cancelled booking history retained

### User Profile

* View profile
* Edit profile
* Profile image
* Change password
* User information management

### Admin Management

* Admin dashboard
* Total users statistics
* Total events statistics
* Total bookings statistics
* Confirmed booking revenue
* User management
* Event management
* Category management
* Booking management

---

## 🛡️ Security Features

Security has been considered throughout the application.

### Prepared Statements

Database queries involving user-controlled data use **prepared statements** to reduce the risk of SQL Injection.

### Password Hashing

Passwords are never stored as plain text.

PHP's password hashing mechanism is used:

```php
password_hash($password, PASSWORD_DEFAULT);
```

Passwords are verified using:

```php
password_verify($password, $hashedPassword);
```

### Session Authentication

Protected pages verify the user's session before displaying sensitive information.

### Role-Based Authorization

The application separates:

```text
User
Admin
```

A normal user cannot directly access protected Admin pages, and an Admin cannot access protected User pages through unauthorized session access.

### Output Escaping

User-generated data displayed in HTML is escaped using:

```php
htmlspecialchars()
```

### POST Request Validation

Sensitive processing operations accept only appropriate HTTP request methods.

### Cache Protection

Protected dashboard pages use HTTP cache-control headers to reduce the possibility of viewing previously authenticated pages after logout.

---

## 🏗️ Project Structure

```text
EventEase/
│
├── admin/
│   ├── dashboard.php
│   ├── login.php
│   ├── add-event.php
│   ├── manage-events.php
│   ├── manage-categories.php
│   ├── manage-bookings.php
│   └── manage-users.php
│
├── assets/
│   ├── css/
│   ├── js/
│   └── uploads/
│
├── auth/
│   ├── login.php
│   ├── login_process.php
│   ├── register.php
│   ├── register_process.php
│   └── logout.php
│
├── booking/
│   ├── book-event.php
│   └── book-event-process.php
│
├── config/
│   ├── config.php
│   └── database.php
│
├── includes/
│   ├── auth-check.php
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
│
├── pages/
│   ├── events.php
│   ├── event-details.php
│   ├── about.php
│   ├── contact.php
│   ├── gallery.php
│   └── services.php
│
├── user/
│   ├── dashboard.php
│   ├── my-bookings.php
│   ├── profile.php
│   ├── edit-profile.php
│   └── change-password.php
│
├── index.php
└── README.md
```

---

## 💻 Technology Stack

### Frontend

* HTML5
* CSS3
* Bootstrap 5
* JavaScript

### Backend

* PHP
* MySQL

### Database

* MySQL
* phpMyAdmin

### Development Environment

* XAMPP
* Apache
* MySQL
* Visual Studio Code

### Version Control

* Git
* GitHub

---

## 🗄️ Main Database Tables

The application uses a relational MySQL database.

Major tables include:

### Users

Stores registered users and administrators.

Important fields include:

```text
id
full_name
email
phone
password
profile_image
role
status
created_at
```

### Events

Stores event information such as:

```text
id
category_id
title
description
venue
event_date
event_time
price
available_seats
image
status
```

### Bookings

Stores user event bookings:

```text
id
user_id
event_id
booking_date
total_amount
booking_status
```

### Categories

Stores event categories used for organizing events.

---

## 🔄 Application Workflow

```text
                    EventEase
                       │
                       ▼
                  Landing Page
                       │
             ┌─────────┴─────────┐
             │                   │
             ▼                   ▼
        User Login          Admin Login
             │                   │
             ▼                   ▼
       User Dashboard      Admin Dashboard
             │                   │
       ┌─────┼─────┐       ┌─────┼──────────┐
       │     │     │       │     │          │
       ▼     ▼     ▼       ▼     ▼          ▼
    Events  Profile Booking Events Users  Bookings
       │
       ▼
 Event Details
       │
       ▼
    Booking
       │
       ▼
   My Bookings
       │
       ▼
 Cancel Booking
```

---

## 🚀 Installation & Setup

### 1. Install XAMPP

Install XAMPP with:

* Apache
* MySQL
* PHP

### 2. Clone the Repository

Open Terminal and run:

```bash
git clone <your-github-repository-url>
```

Move into the project directory:

```bash
cd EventEase
```

### 3. Place Project in XAMPP

Place the project inside:

```text
xampp/htdocs/
```

The final path should look like:

```text
htdocs/EventEase/
```

### 4. Start XAMPP

Start:

```text
Apache
MySQL
```

### 5. Create Database

Open:

```text
http://localhost/phpmyadmin
```

Create the required EventEase database.

Import the project's SQL database file if provided.

### 6. Configure Database

Update the database configuration in:

```text
EventEase/config/database.php
```

with your local MySQL credentials.

### 7. Run the Project

Open:

```text
http://localhost/EventEase/
```

---

## 👤 User Testing Flow

A normal user can test the application using the following flow:

```text
Register
   ↓
Login
   ↓
User Dashboard
   ↓
Browse Events
   ↓
View Event Details
   ↓
Book Event
   ↓
My Bookings
   ↓
Cancel Booking
   ↓
Cancelled Booking History
   ↓
Logout
```

---

## 🔐 Admin Testing Flow

Admin testing:

```text
Admin Login
   ↓
Admin Dashboard
   ↓
Manage Categories
   ↓
Add / Manage Events
   ↓
Manage Users
   ↓
Manage Bookings
   ↓
Check Revenue
   ↓
Logout
```

---

## 🧪 Security & Functional Testing

The application has been tested for important scenarios including:

* Valid user registration
* Invalid email validation
* Password validation
* Password confirmation
* Duplicate email registration
* User login
* Invalid login credentials
* Admin login
* Role-based access
* User dashboard protection
* Admin dashboard protection
* Logout
* Browser back-button behavior after logout
* Event listing
* Event details
* Event booking
* Available seat validation
* Booking cancellation
* Cancelled booking count handling
* User-specific booking visibility
* SQL injection protection
* Password hashing
* Output escaping

---

## 📊 Project Goals

The main objectives of EventEase are:

1. To provide a centralized platform for event management.
2. To simplify event discovery and booking.
3. To provide secure user authentication.
4. To provide role-based access for Users and Admins.
5. To allow administrators to efficiently manage events and bookings.
6. To maintain booking and user information using a relational database.
7. To provide a responsive and user-friendly interface.

---

## 🔮 Future Scope

The project can be further enhanced with:

* Online payment gateway integration
* Email booking confirmation
* QR-code based event tickets
* Automated email notifications
* Event reminders
* Advanced event search and filtering
* Reviews and ratings
* Wishlist functionality
* Analytics dashboard
* REST API integration
* Mobile application
* AI-based event recommendations

---

## 📈 Advantages

* Centralized event management
* Easy event discovery
* Simple booking process
* Secure authentication
* Role-based access control
* User-specific booking management
* Admin management panel
* Real-time seat availability tracking
* Responsive interface
* Database-driven architecture

---

## 🎓 Academic Project

**Project Name:** EventEase — Event Management System

**Project Type:** MCA Major Project

**Domain:** Web Application Development

**Backend:** PHP

**Database:** MySQL

**Frontend:** HTML, CSS, Bootstrap, JavaScript

---

## 👩‍💻 Developer

**Vanshika Sharma**

Master of Computer Applications (MCA)

---

## 📄 License

This project was developed as an academic MCA Major Project.

It may be used for educational and learning purposes.
