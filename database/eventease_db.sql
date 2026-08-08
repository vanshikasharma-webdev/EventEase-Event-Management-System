-- ===========================================
-- EventEase Database
-- ===========================================

USE eventease_db;

-- ===========================================
-- ADMINS TABLE
-- ===========================================

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins
(name,email,password)
VALUES
(
'Administrator',
'admin@eventease.com',
'admin123'
);

-- ===========================================
-- USERS TABLE
-- ===========================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'default.png',
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users
(full_name,email,phone,password)
VALUES

('Rahul Sharma',
'rahul@gmail.com',
'9876543210',
'123456'),

('Priya Singh',
'priya@gmail.com',
'9876501234',
'123456');
-- ===========================================
-- CATEGORIES TABLE
-- ===========================================

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories
(category_name,description)
VALUES

('Wedding','Wedding Events'),

('Birthday','Birthday Celebration'),

('Corporate','Corporate Meetings'),

('Seminar','Educational Seminars'),

('Concert','Music Concerts');
-- ===========================================
-- EVENTS TABLE
-- ===========================================

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,

    category_id INT NOT NULL,

    title VARCHAR(150) NOT NULL,

    description TEXT NOT NULL,

    venue VARCHAR(200) NOT NULL,

    event_date DATE NOT NULL,

    event_time TIME NOT NULL,

    price DECIMAL(10,2) NOT NULL,

    available_seats INT NOT NULL,

    image VARCHAR(255) DEFAULT 'default-event.jpg',

    status ENUM('Upcoming','Completed','Cancelled')
        DEFAULT 'Upcoming',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_event_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

INSERT INTO events
(
category_id,
title,
description,
venue,
event_date,
event_time,
price,
available_seats
)

VALUES

(
1,
'Royal Wedding Expo',
'Premium Wedding Planning Event',
'Agra Convention Hall',
'2026-10-10',
'10:00:00',
4999.00,
300
),

(
3,
'Corporate Leadership Summit',
'Business Networking Event',
'Gurugram',
'2026-11-15',
'11:00:00',
1999.00,
250
);

-- ===========================================
-- BOOKINGS TABLE
-- ===========================================

CREATE TABLE bookings (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    event_id INT NOT NULL,

    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    total_amount DECIMAL(10,2) NOT NULL,

    booking_status ENUM('Pending','Confirmed','Cancelled')
        DEFAULT 'Pending',

    CONSTRAINT fk_booking_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_booking_event
        FOREIGN KEY (event_id)
        REFERENCES events(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

INSERT INTO bookings
(
user_id,
event_id,
total_amount
)

VALUES

(
1,
1,
4999
),

(
2,
2,
1999
);
-- ===========================================
-- CONTACTS TABLE
-- ===========================================

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL,

    subject VARCHAR(150) NOT NULL,

    message TEXT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- GALLERY TABLE
-- ===========================================

CREATE TABLE gallery (

    id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(150) NOT NULL,

    image VARCHAR(255) NOT NULL,

    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- FAQ TABLE
-- ===========================================

CREATE TABLE faq (

    id INT AUTO_INCREMENT PRIMARY KEY,

    question VARCHAR(255) NOT NULL,

    answer TEXT NOT NULL,

    status ENUM('Active','Inactive')
        DEFAULT 'Active'
);

INSERT INTO faq
(question,answer)

VALUES

(
'How do I book an event?',
'Register, login and click Book Now.'
),

(
'Can I cancel my booking?',
'Yes, before the event starts.'
);