MediCare — Doctor Appointment & Real-Time Chat Platform

A modern clinic management platform built for Prof. Dr. Awais Malik, Lahore, featuring online appointment booking, role-based dashboards, appointment management, and real-time patient support chat.

✨ Key Features
Public Portal: Doctor profile, medical services with PKR pricing, clinic locations (FMH Shadman & Mid City), and contact form.
Patient Portal: Multi-step appointment booking, available time slots, appointment history, and real-time support chat.
Doctor Dashboard: Manage daily schedules, review appointment requests, and update appointment statuses.
Admin Dashboard: Revenue statistics, appointment analytics, contact inbox, and live chat management.
Real-Time Chat: WhatsApp-style message status ticks, online presence, last seen, message replies, emoji picker, file attachments, and message deletion for yourself or everyone.
🛠️ Tech Stack
Backend: Laravel 13, PHP 8.4
Frontend: Vue 3, Inertia.js 2, Tailwind CSS 3
Real-Time Communication: Laravel Reverb, Laravel Echo
Database: SQLite
Storage: Local file storage
📋 Requirements
PHP 8.4 or higher (with sqlite3, pdo_sqlite, mbstring, fileinfo extensions)
Composer 2.x
Node.js 20+ and npm
Git
🔐 Demo Credentials
Role	Email	Password
Admin	awais@gmail.com	awais@720
Doctor	ahmed@medicare.test	password
Patient	patient@medicare.test	password

Note: These credentials are intended for local development and demonstration only. Never use default demo credentials in a production environment.

🚀 Installation & Setup
1. Clone the Repository
bash
git clone <your-repository-url>
cd <project-folder>
2. Install Dependencies
bash
composer install
npm install
3. Configure Environment
bash
cp .env.example .env
php artisan key:generate

Configure your .env file to use SQLite and ensure the database file exists:

bash
touch database/database.sqlite
env
DB_CONNECTION=sqlite
BROADCAST_CONNECTION=reverb

Make sure the Reverb variables (REVERB_APP_ID, REVERB_APP_KEY, REVERB_APP_SECRET, REVERB_HOST, REVERB_PORT, REVERB_SCHEME) are set, along with their matching VITE_REVERB_* values. If they are missing, run:

bash
php artisan install:broadcasting
4. Set Up the Database
bash
php artisan migrate:fresh --seed
php artisan storage:link

Note: migrate:fresh drops all existing database tables before recreating them. Use it only when resetting a development database.

5. Build Frontend Assets
bash
npm run build
▶️ Running the Application

Open separate terminal windows and run the following commands.

Terminal 1 — Laravel Server

bash
php artisan serve

Terminal 2 — Laravel Reverb

bash
php artisan reverb:start

Terminal 3 — Frontend Development Server (Optional)

For frontend development with hot module replacement:

bash
npm run dev

Terminal 4 — Queue Worker (If queues are enabled)

bash
php artisan queue:listen

The app will be available at http://localhost:8000.

🧪 Testing
bash
php artisan test
🔧 Troubleshooting
Chat messages not updating live: Make sure php artisan reverb:start is running and your VITE_REVERB_* values match the REVERB_* values. Rebuild assets after changing .env (npm run build).
Uploaded files or images not showing: Run php artisan storage:link.
Database errors: Confirm database/database.sqlite exists and DB_CONNECTION=sqlite is set.
Blank page or missing styles: Run npm run build or keep npm run dev running.
🔒 Security Notes
Change all demo credentials before deploying.
Set APP_ENV=production and APP_DEBUG=false in production.
Use a production-grade database (MySQL/PostgreSQL) and secure your Reverb server with TLS for live deployments.
📄 License

This project is open-sourced under the MIT License.
