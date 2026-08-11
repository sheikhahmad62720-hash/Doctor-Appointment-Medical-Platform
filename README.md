<p align="center"><img src="https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel" alt="Laravel 13"> <img src="https://img.shields.io/badge/Inertia-2-blue?style=for-the-badge" alt="Inertia 2"> <img src="https://img.shields.io/badge/Vue-3-42b883?style=for-the-badge&logo=vue.js" alt="Vue 3"> <img src="https://img.shields.io/badge/Tailwind-3-38bdf8?style=for-the-badge&logo=tailwindcss" alt="Tailwind 3"></p>

# MediCare — Doctor Appointment Booking System

A complete clinic appointment platform for **Prof. Dr. Awais Malik** (Bariatric, Laparoscopic & General Surgeon, Lahore). Patients can browse services, book consultations, and manage appointments, while the doctor and admin get dedicated dashboards.

## Features

### Public website
- **Home** — hero, doctor profile, services preview, patient reviews, CTA.
- **About** — doctor bio, qualifications, stats and values.
- **Services** — all services with procedures, pricing and details.
- **Service detail pages** — per-service overview, procedures, clinic locations and booking card.
- **Contact** — clinic info (FMH Shadman & Mid City Jail Road) and a working contact form.

### Patients
- Register / login.
- Multi-step booking flow: **Service → Consultation type → Date & time (live availability) → Patient details → Payment**.
- Appointment confirmation page with clinic details.
- Personal dashboard to view and cancel appointments.

### Doctor
- Doctor dashboard with today's appointments, pending requests and slot availability.
- Update appointment status (confirm / complete / cancel).

### Admin
- Admin dashboard with stats: appointments, patients, revenue (PKR), monthly revenue, pending payments.
- Last-7-days appointment trend + status breakdown.
- Recent appointments, patients and reviews.
- **Contact messages** inbox with **read / unread** toggle.

## Tech Stack

| Layer      | Tech                                    |
| ---------- | --------------------------------------- |
| Backend    | Laravel 13 (PHP 8.4)                    |
| Frontend   | Inertia 2 · Vue 3 · Tailwind CSS 3      |
| Build      | Vite 8 · @heroicons/vue                 |
| Database   | SQLite (out of the box)                 |

## Demo Accounts

| Role   | Email                | Password     |
| ------ | -------------------- | ------------ |
| Admin  | `awais@gmail.com`    | `awais@720`  |
| Doctor | `ahmed@medicare.test`| `password`   |
| Patient| `patient@medicare.test` | `password` |

## Setup

```bash
composer install
npm install
cp .env.example .env        # or copy manually on Windows
php artisan key:generate
```

Configure the database in `.env` (defaults to SQLite — the file already exists at `database/database.sqlite`).

```bash
php artisan migrate:fresh --seed
npm run build               # production assets
php artisan serve           # http://127.0.0.1:8000
```

For development with hot reload instead of `npm run build`:

```bash
npm run dev
```

## Editing Site Content

All public website content lives in a single file:

> **`resources/js/config/site.js`**

It holds the doctor profile, clinics, services (with procedures & PKR pricing) and reviews.

- Edit the file → run `npm run build` (or use `npm run dev` for hot reload).
- Services/pricing are also seeded to the database for booking & dashboards — after changing those, run `php artisan db:seed`.

The seeder (`database/seeders/DatabaseSeeder.php`) only creates the login users and seeds services from `site.js` — no fake appointments or messages.

## Data Model

- **users** — patients, doctor, admin. Doctor profile includes `clinics` (JSON) and `qualifications`.
- **services** — surgical services with `procedures` (JSON), price, duration and consultation type.
- **appointments** — bookings linked to patient, doctor and service, with status and payment status.
- **reviews** — patient feedback linked to completed appointments.
- **contact_messages** — messages from the contact form, with a `is_read` flag for the admin inbox.

## Key Routes

| Method | URI                     | Access            |
| ------ | ----------------------- | ----------------- |
| GET    | `/`                     | Public            |
| GET    | `/services`             | Public            |
| GET    | `/services/{slug}`      | Public            |
| POST   | `/contact`              | Public            |
| GET    | `/book`                 | Auth              |
| POST   | `/book`                 | Auth              |
| GET    | `/dashboard`            | Patient           |
| GET    | `/doctor/dashboard`     | Doctor            |
| GET    | `/admin/dashboard`      | Admin             |
| POST   | `/admin/messages/{id}/toggle` | Admin        |

## License

Open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
