# PinkBook – Simple Appointment Booking System

PHP + MySQL (mysqli) + Bootstrap 5 with a pink theme.

## Features
- Register / Login / Logout (`password_hash`, `password_verify`, sessions)
- Roles: **admin** and **user**
- Users: book, view, edit, delete their own appointments
- Admin: view all appointments, change status (Pending / Confirmed / Cancelled), edit, delete
- Double-booking prevention for the same date and time
- Client-side (HTML5 + JS) and server-side (PHP) validation
- Prepared statements (SQL injection) and `htmlspecialchars` (XSS)

## Files
| File | Purpose |
| --- | --- |
| `connection.php` | Database connection + small helper functions |
| `header.php` / `footer.php` | Shared layout and JavaScript |
| `index.php` | Home page |
| `register.php`, `login.php`, `logout.php` | Authentication |
| `dashboard.php` | User: create, read, delete appointments |
| `edit.php` | Update an appointment |
| `admin.php` | Admin panel |
| `style.css` | Pink theme |
| `database/schema.sql` | Tables + default admin |

## Setup (XAMPP)
1. Start **Apache** and **MySQL**.
2. Open `http://localhost/phpmyadmin` and create a database named `appointmentdb`.
3. Import `database/schema.sql`.
4. Copy this folder to `htdocs/` and open `http://localhost/appointment-system/`.

## Deploy (InfinityFree)
1. Import `database/schema.sql` into your InfinityFree database via phpMyAdmin.
2. In `connection.php`, comment out the LOCAL block and uncomment the INFINITYFREE block (add your vPanel password).
3. Upload all files to `htdocs/`.

## Admin test credentials
- Email: `admin@pinkbook.com`
- Password: `Admin@123`
