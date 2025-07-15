# 💬 Chat-Realtime

A real-time chat application built using the **TALL Stack**  
(**Tailwind CSS**, **Alpine.js**, **Laravel**, **Livewire**) + **Pusher** + **MySQL**

## 📸 Preview

![Chat Screenshot 1](screenshots/chat-1.png)
![Chat Screenshot 2](screenshots/chat-2.png)

---

## 🚀 Features

- ⚡ Real-time messaging (via Pusher)
- 🔔 Sound notifications on new messages
- 🎵 Ability to change notification sounds
- 💬 Chat without page refresh (powered by Livewire)
- 🖼️ Send & receive images in chat
- 🧩 Built with Laravel Breeze (auth & structure)
- 📱 Fully responsive – works on all screen sizes

---

## 🛠️ Tech Stack

- **Laravel** + **Livewire**
- **Tailwind CSS**
- **Alpine.js**
- **Pusher**
- **MySQL**

---

## 📦 Installation

```bash
# 1. Clone the repository
git clone https://github.com/MahmoudSalah53/Chat-Realtime.git
cd Chat-Realtime

# 2. Install PHP dependencies
composer install

# 3. Install JavaScript dependencies
npm install && npm run dev

# 4. Copy .env and generate app key
cp .env.example .env
php artisan key:generate

# 5. Set up your database in the .env file, then run:
php artisan migrate

# 6. Start the queue worker for real-time events
php artisan queue:work

# 7. Run the local development server
php artisan serve
