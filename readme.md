# 🎬 My Movie Application (Legacy Stack)

A responsive movie catalog application built on **Laravel 5.2** and **PHP 5.6**, fully containerized with **Docker**. This project demonstrates how to modernize a legacy stack to consume external APIs (OMDb), manage complex database relationships, and deliver a smooth user experience with shimmer loading effects.

### 1. Login Page
<img width="1869" height="934" alt="Screenshot From 2026-02-16 13-05-43" src="https://github.com/user-attachments/assets/85969745-9fa6-4395-bcb1-e36d6353469f" />

### 2. Register Page
<img width="1869" height="934" alt="Screenshot From 2026-02-16 13-05-50" src="https://github.com/user-attachments/assets/7ee73a46-5e73-423c-a2de-7e980bfed359" />

### 3. Main Page
<img width="1869" height="934" alt="Screenshot From 2026-02-16 13-05-22" src="https://github.com/user-attachments/assets/8a3fb26f-5e39-4ca1-b6e4-c80e64fa1bbe" />

### 4. Details Page
<img width="1869" height="934" alt="Screenshot From 2026-02-16 13-05-37" src="https://github.com/user-attachments/assets/62c5a528-419e-433b-b4ee-e527da669832" />

### 5. Favorite Page
<img width="1869" height="934" alt="Screenshot From 2026-02-16 13-05-31" src="https://github.com/user-attachments/assets/e9ad276a-7e67-4ddd-9b12-81c13a22076c" />

---

## 🛠 Tech Stack

* **Framework:** Laravel 5.2
* **Language:** PHP 5.6 (Alpine/Debian)
* **Database:** MySQL 5.7 (Dockerized)
* **Frontend:** Bootstrap 3 + Custom CSS Flexbox
* **Infrastructure:** Docker & Docker Compose

---

## 🚀 Installation & Setup

Follow these steps to get the application running on your local machine.

### 1. Prerequisites
Ensure you have **Docker** and **Docker Compose** installed.

### 2. Clone the Repository
```bash
git clone https://github.com/AlbaniZaidan/my-movie-web
cd my-movie
```

### 3. Build & Start Containers

Start the Docker containers in the background. This may take a while the first time as it builds the PHP 5.6 image.

```bash
sudo docker compose up -d --build
```

### 4. Install PHP Dependencies

Install the dependencies inside the container using Composer 1.x:

```bash
sudo docker compose exec app composer install
```

### 5. Environment Setup

Create the .env file and generate the application encryption key:

```bash
cp .env.example .env
sudo docker compose exec app php artisan key:generate
```

### 6. Fix Permissions (Crucial for 500 Errors)

Laravel 5 requires specific write permissions for storage and caching. Run this to prevent "Internal Server Error" issues

```bash
# Create missing directories
mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} storage/logs

# Set ownership to www-data (Web Server User)
sudo docker compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
sudo docker compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```

---

## 🧠 The Challenge & The Solution

**The Problem: API Limitations & Performance**  

We faced a significant challenge when trying to display a robust list of "Popular Movies":

**1. Incomplete Data:** The OMDb API's "Search" endpoint (s=Batman) only returns basic data (Title, Year, Poster). It omits critical details like Genre, Plot, Actors, and IMDb Rating.

**2. Performance Bottlenecks:** Fetching full details for 20+ movies on every page load caused extreme latency (3+ seconds load time) and quickly hit API rate limits.

**3. UI Gaps:** Without the rating or genre data available instantly, the UI cards looked broken and empty.

**The Solution: Database-First Architecture**
We solved this by decoupling the Data Fetching from the User View. Instead of calling the API when the user visits the page, we pre-fetch and store the data in our MySQL database.

**Step 1: The Migration Strategy**

We designed a schema that supports UUIDs (instead of standard auto-increment IDs) to prevent ID collisions during bulk imports.

**Step 2: The Two-Stage Seeding Process**

We implemented a "smart" seeding pipeline:

**1. Stage 1 (MovieListSeeder):** Loops through popular franchises (Star Wars, Avengers, etc.) and saves the basic list (IMDb IDs) to a temporary table.

**2. Stage 2 (MovieDetailsSeeder):** Reads that list, hits the OMDb API for the Full Details (i=tt1234567), and saves the rich data (Plot, Actors, Ratings) into the final movies_table.

**How to Execute the Solution:**

Run these commands to populate your database with rich data:

```bash
# 1. Run Migrations
sudo docker compose exec app php artisan migrate

# 2. Refresh Autoloader (Required for new Seeders in Laravel 5)
sudo docker compose exec app composer dump-autoload

# 3. Seed the Basic List (Fetch IDs)
sudo docker compose exec app php artisan db:seed --class=MovieListSeeder

# 4. Seed the Full Details (Fetch Plot/Ratings)
sudo docker compose exec app php artisan db:seed --class=MovieDetailsSeeder
```
