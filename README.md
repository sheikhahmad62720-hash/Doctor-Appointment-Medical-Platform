# MediCare — Doctor Appointment & Real-Time Chat Platform

A modern clinic management platform built for **Prof. Dr. Awais Malik, Lahore**, featuring online appointment booking, role-based dashboards, appointment management, and real-time patient support chat.

## ✨ Key Features

- **Public Portal:** Doctor profile, medical services with PKR pricing, clinic locations (FMH Shadman & Mid City), and contact form.
- **Patient Portal:** Multi-step appointment booking, available time slots, appointment history, and real-time support chat.
- **Doctor Dashboard:** Manage daily schedules, review appointment requests, and update appointment statuses.
- **Admin Dashboard:** Revenue statistics, appointment analytics, contact inbox, and live chat management.
- **Real-Time Chat:** WhatsApp-style message status ticks, online presence, last seen, message replies, emoji picker, file attachments, and message deletion for yourself or everyone.

## 🛠️ Tech Stack

- **Backend:** Laravel 13, PHP 8.4
- **Frontend:** Vue 3, Inertia.js 2, Tailwind CSS 3
- **Real-Time Communication:** Laravel Reverb, Laravel Echo
- **Database:** SQLite
- **Storage:** Local file storage

## 🔐 Demo Credentials

| Role | Email | Password |
|---|---|---|
| Admin | `awais@gmail.com` | `awais@720` |
| Doctor | `ahmed@medicare.test` | `password` |
| Patient | `patient@medicare.test` | `password` |

> **Note:** These credentials are intended for local development and demonstration only. Never use default demo credentials in a production environment.

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd <project-folder>
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` file to use SQLite and ensure the database file exists.

### 4. Set Up the Database

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

**Note:** `migrate:fresh` drops all existing database tables before recreating them. Use it only when resetting a development database.

### 5. Build Frontend Assets

```bash
npm run build
```

## ▶️ Running the Application

Open separate terminal windows and run the following commands.

**Terminal 1 — Laravel Server**

```bash
php artisan serve
```

**Terminal 2 — Laravel Reverb**

```bash
php artisan reverb:start
```

**Terminal 3 — Frontend Development Server (Optional)**

For frontend development with hot module replacement:

```bash
npm run
