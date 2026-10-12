<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel" alt="Laravel 13">
  <img src="https://img.shields.io/badge/Inertia-2-blue?style=for-the-badge" alt="Inertia 2">
  <img src="https://img.shields.io/badge/Vue-3-42b883?style=for-the-badge&logo=vue.js" alt="Vue 3">
  <img src="https://img.shields.io/badge/Tailwind-3-38bdf8?style=for-the-badge&logo=tailwindcss" alt="Tailwind 3">
  <img src="https://img.shields.io/badge/Reverb-2ea669?style=for-the-badge&logo=websocket" alt="Laravel Reverb">
</p>

# MediCare — Doctor Appointment & Real-Time Chat Platform

A modern clinic management system built for **Prof. Dr. Awais Malik** (Lahore). It features online appointment booking, multi-role dashboards, and live WebSocket chat between patients and clinic administration.

---

## Key Features

* **Public Portal:** Doctor bio, services with PKR pricing, clinic locations (FMH Shadman & Mid City), and contact form.
* **Patient Portal:** Multi-step booking flow, live slot selection, appointment history, and real-time support chat.
* **Doctor Dashboard:** Manage daily schedule, pending requests, and update appointment statuses.
* **Admin Dashboard:** Revenue stats (PKR), appointment analytics, contact inbox, and real-time chat dashboard.
* **Real-Time Chat (Laravel Reverb):** WhatsApp-style status ticks, online presence/last seen, message replies, emoji picker, file attachments, and message deletion (for me / everyone).

---

## Tech Stack

* **Backend:** Laravel 13 (PHP 8.4)
* **Frontend:** Inertia.js 2 · Vue 3 · Tailwind CSS 3
* **Real-time:** Laravel Reverb · Laravel Echo
* **Database & Storage:** SQLite · Local Storage

---

## Demo Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `awais@gmail.com` | `awais@720` |
| **Doctor** | `ahmed@medicare.test` | `password` |
| **Patient** | `patient@medicare.test` | `password` |

---

## Quick Setup

1. **Install Dependencies:**
   ```bash
   composer install && npm install
   cp .env.example .env
   php artisan key:generate

##Database & Storage Setup:
php artisan migrate:fresh --seed
php artisan storage:link
npm run build

##Start Servers
php artisan serve         
php artisan reverb:start
