
# Server-Client Restaurant 🍽️

This is a Laravel 10 demo project that simulates a "server requests file from client" workflow, implemented using a simple polling mechanism.  
The **server** cannot directly download files from the client; instead, the **client polls** the server for tasks and uploads the requested file when needed.

---

## 🚀 1. Setup Instructions

### Requirements
- PHP 8.1+
- Composer
- SQLite (built-in PHP driver)

### Installation
```bash
git clone https://github.com/nuril91/server-client-restaurant.git
cd server-client-restaurant
composer install
cp .env.example .env
php artisan key:generate
```

If you're on **Windows**, make sure SQLite drivers are enabled in your PHP.ini:
```ini
extension=pdo_sqlite
extension=sqlite3
```

Then create the database file:
```bash
type nul > database\database.sqlite   # (Windows)
# or
touch database/database.sqlite         # (Linux/Mac)
```

Run migrations:
```bash
php artisan migrate
```

---

## 🧩 2. Configuration

Edit your `.env` for database settings.

### ✅ Recommended for Windows:
```
DB_CONNECTION=sqlite
DB_DATABASE=C:\laragon\www\server-client-restaurant\database\database.sqlite
```

### ✅ For Linux/Mac:
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

---

## ⚙️ 3. How It Works

1. **Server creates a Task** - tells the client to upload a file.
2. **Client polls the server** - asks if any task is pending.
3. **If task found**, the client uploads a local file to the server.
4. Server saves the file to `storage/app/uploads` and marks the task `completed`.

---

## 🧠 4. API Endpoints

| Method | Endpoint | Description |
|--------|-----------|-------------|
| POST | `/api/tasks` | Create a new task |
| GET | `/api/tasks/{clientId}` | Client polls for tasks |
| PATCH | `/api/tasks/{id}` | Update task status |
| POST | `/api/upload` | Upload a file from client |

---

## 💻 5. Example Workflow

### Server side
```bash
php artisan serve
```

### Create a download request
```bash
curl -X POST http://127.0.0.1:8000/api/tasks   -H "Content-Type: application/json"   -d '{"client_id":"client_01","action":"send_file"}'
```

### Client side (simulating client poll)
```bash
php artisan client:poll client_01 --server=http://127.0.0.1:8000/api
```

The uploaded file will appear in:
```
storage/app/uploads/
```

OR you can manually test in file `test.http`

---

## ⚠️ 6. Troubleshooting

### Error: `could not find driver (sqlite)`
Enable `pdo_sqlite` and `sqlite3` extensions in your `php.ini`.

### Error: `Database file at path does not exist`
Make sure the file `database/database.sqlite` exists (create it manually if needed).

### Windows Tip
Sometimes Laravel needs an **absolute path** for SQLite on Windows.

---

## 🧾 License
MIT License © 2025  
Created by Nur Ilham
