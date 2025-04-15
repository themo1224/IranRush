# Iran Rush API 🚀

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Redis](https://img.shields.io/badge/Redis-DC382D?style=for-the-badge&logo=redis&logoColor=white)](https://redis.io)
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![AWS S3](https://img.shields.io/badge/AWS_S3-569A31?style=for-the-badge&logo=amazon-s3&logoColor=white)](https://aws.amazon.com/s3/)

Modular Laravel API backend for Iran Rush platform with Redis caching, Dockerized infrastructure, and cloud storage.

> **Note:** This project is currently under active development

## 🌟 Key Features

- **Modular Architecture** 
- **Redis Integration**:
  - OTP Caching
  - Queue Management (planned)
  - Performance Optimization (planned)
- **Dockerized Services** (Redis + MySQL + Laravel)
- **Cloud File Storage** (AWS S3)
- **RESTful API Standards**
- JWT Authentication

## 🛠 Tech Stack

**Core:**
- PHP 8.1+ / Laravel 10
- MySQL 8 (Main Database)
- Redis 7 (Caching/Queues)

**Infrastructure:**
- Docker & Docker Compose
- AWS S3 (File Storage)
- Nginx (Web Server)

**Dev Tools:**
- PHPUnit (Testing)
- Pest (Alternative Testing)
- Laravel Sail (Local Dev)

## 🚀 Getting Started

### Prerequisites
- Docker & Docker Compose
- PHP 8.1+
- Composer

### Installation
1. Clone the repo:
   ```bash
   git clone https://github.com/yourusername/iran-rush-api.git
   cd iran-rush-api
