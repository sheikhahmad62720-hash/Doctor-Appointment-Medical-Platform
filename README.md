<p align="center"><img src="https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel" alt="Laravel 13"> <img src="https://img.shields.io/badge/Inertia-2-blue?style=for-the-badge" alt="Inertia 2"> <img src="https://img.shields.io/badge/Vue-3-42b883?style=for-the-badge&logo=vue.js" alt="Vue 3"> <img src="https://img.shields.io/badge/Tailwind-3-38bdf8?style=for-the-badge&logo=tailwindcss" alt="Tailwind 3"> <img src="https://img.shields.io/badge/Reverb-2ea669?style=for-the-badge&logo=websocket" alt="Laravel Reverb"></p>

# MediCare — Doctor Appointment Booking System

A complete clinic appointment platform for **Prof. Dr. Awais Malik** (Bariatric, Laparoscopic & General Surgeon, Lahore). Patients can browse services, book consultations, manage appointments, and chat with the clinic in real time, while the doctor and admin get dedicated dashboards.

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
- **Real-time chat with the clinic** (patient ↔ admin).

### Doctor
- Doctor dashboard with today's appointments, pending requests and slot availability.
- Update appointment status (confirm / complete / cancel).

### Admin
- Admin dashboard with stats: appointments, patients, revenue (PKR), monthly revenue, pending payments.
- Last-7-days appointment trend + status breakdown.
- Recent appointments, patients and reviews.
- **Contact messages** inbox with **read / unread** toggle.
- **Real-time chat inbox** — all patient conversations in one sidebar with unread badges.

### Real-time chat (patient ↔ admin)
- **Instant messaging** over WebSocket (Laravel Reverb + Echo) — sent & received messages appear live, no refresh.
- **WhatsApp-style ticks** — single grey (sent), double grey (delivered), double blue/green (read).
- **Online status & last seen** — live presence channel; "Online" vs "Last seen …" in chat header and admin sidebar.
- **Replies** — reply to any message with an inline quoted preview that scrolls to the original.
- **Emoji picker** — 80 emoji grid in the composer.
- **Attachments** — image previews and downloadable files (10 MB max, stored on the public disk).
- **Delete for me** — hides a message just for you (no broadcast).
- **Delete for everyone** — removes the message for both sides (broadcast live, shows a "deleted" placeholder).
- **Copy message** — one-click copy of any message text.

## Tech Stack

| Layer      | Tech                                    |
| ---------- | --------------------------------------- |
| Backend    | Laravel 13 (PHP 8.4)                    |
| Frontend   | Inertia 2 · Vue 3 · Tailwind CSS 3      |
| Build      | Vite 8 · @heroicons/vue                 |
| Real-time  | Laravel Reverb · laravel-echo · pusher-js |
| Database   | SQLite (out of the box)                 |
| Storage    | Local disk (`storage/app/public`)       |

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

Make sure these broadcasting keys are present in `.env` (used by the chat):

```
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=doctor-appointment
REVERB_APP_KEY=medicare-key
REVERB_APP_SECRET=medicare-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
VITE_REVERB_APP_KEY=medicare-key
VITE_REVERB_HOST=127.0.0.1
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

Run migrations, link storage (needed for chat attachments), and build assets:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
npm run build               # production assets
```

### Running the app

Start the HTTP server **and** the Reverb WebSocket server (both are required for real-time chat):

```bash
php artisan serve           # http://127.0.0.1:8000
php artisan reverb:start    # ws://127.0.0.1:8080
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

- **users** — patients, doctor, admin. Doctor profile includes `clinics` (JSON) and `qualifications`; `last_seen_at` tracks presence for chat.
- **services** — surgical services with `procedures` (JSON), price, duration and consultation type.
- **appointments** — bookings linked to patient, doctor and service, with status and payment status.
- **reviews** — patient feedback linked to completed appointments.
- **contact_messages** — messages from the contact form, with a `is_read` flag for the admin inbox.
- **conversations** — a 1:1 thread between a patient and the admin, with `last_message_at` for sorting.
- **messages** — chat messages with sender, `type` (text/image/file), attachment metadata, `reply_to_id` (self-referencing), `deleted_for` (JSON list of users who deleted for themselves) and `deleted_at` (soft delete for everyone).

## Real-time Architecture

- **Server**: Laravel Reverb (`artisan reverb:start`) handles WebSocket connections on port 8080.
- **Client**: `laravel-echo` + `pusher-js` configured in `resources/js/echo.js` using the `VITE_REVERB_*` env vars.
- **Private channel** `private-chat.{conversation}` — `MessageSent`, `MessageStatusUpdated` and `MessageDeleted` events deliver new messages, read/delivered ticks and deletions live.
- **Presence channel** `presence-online` — tracks who is online; the client sends a heartbeat every 25s to update `last_seen_at`.
- **Channel auth** — `routes/channels.php` guards each conversation (`chat.{conversation}`) by membership and returns the online member payload for `presence-online`.
- **Message payloads** — serialized once in `app/Support/ChatMessageSerializer.php` (reply snippet, file URL, ticks, deleted flag, timestamps).

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
| GET    | `/chat`                 | Patient           |
| GET    | `/admin/chat`           | Admin             |
| GET    | `/chat/{conversation}`  | Member (messages) |
| POST   | `/chat/{conversation}`  | Member (send)     |
| POST   | `/chat/{conversation}/attach` | Member      |
| POST   | `/chat/{conversation}/read` · `/delivered` | Member |
| POST   | `/chat/{conversation}/messages/{message}/delete-me` | Sender |
| POST   | `/chat/{conversation}/messages/{message}/delete-everyone` | Sender |
| GET    | `/chat/{conversation}/peer-status` | Member  |
| POST   | `/presence/heartbeat`   | Auth              |

## License

Open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
