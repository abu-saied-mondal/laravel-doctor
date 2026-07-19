# Laravel Doctor 🩺

[![Packagist Version](https://img.shields.io/packagist/v/devski/laravel-doctor.svg?style=flat-square)](https://packagist.org/packages/devski/laravel-doctor)
[![Total Downloads](https://img.shields.io/packagist/dt/devski/laravel-doctor.svg?style=flat-square)](https://packagist.org/packages/devski/laravel-doctor)
[![License](https://img.shields.io/packagist/l/devski/laravel-doctor.svg?style=flat-square)](https://github.com/devski/laravel-doctor/blob/main/LICENSE.md)

> **Laravel Doctor doesn't just tell developers what's broken. It explains why it's broken, what the impact is, whether it's safe to fix, and can repair it automatically.**

---

Laravel Doctor is an open-source diagnostics and repair toolkit for Laravel applications. It provides a health check platform to inspect, explain, repair, and optimize your application states with surgical precision.

---

## 🚀 Features

- 🔍 **Comprehensive Diagnostics**: Automatically scan storage, permissions, databases, queues, cache, configurations, keys, and mail configurations.
- 💡 **Contextual Explanations**: Understand the exact consequences and risks of failed checks.
- 🛠️ **Safe Auto-Repairs**: Instantly apply safe, surgical fixes for common configuration and system issues.
- 📊 **Flexible Reporting**: Output diagnostics in beautifully formatted CLI tables, markdown documentation, or structured JSON.
- ⚡ **Production & Performance Modes**: Separate specialized rules for live systems and performance speed-ups.

---

## 📦 Installation

Install the package via Composer:

```bash
composer require devski/laravel-doctor --dev
```

You can publish the configuration file using:

```bash
php artisan vendor:publish --tag="doctor-config"
```

---

## 💻 Commands

### 1. Doctor Scan
Scan your Laravel application for standard health issues:
```bash
php artisan doctor:scan
```

### 2. Doctor Interactive Repair
Interactive tool to safely repair detected issues:
```bash
php artisan doctor:repair
```

### 3. Production Validator
Validate environment flags, secure headers, permissions, and caches for a production release:
```bash
php artisan doctor:production
```

### 4. Performance Optimizer
Scan your application for database, configuration, route, and view caching optimizations:
```bash
php artisan doctor:performance
```

### 5. Export Report
Generate and save a detailed report:
```bash
php artisan doctor:report --format=markdown
```

---

## 📸 Screenshots

*CLI Interactive Scan Output:*
```
+------------------+----------+----------------------------+-----------------------+
| Check            | Status   | Issue                      | Repair Available?     |
+------------------+----------+----------------------------+-----------------------+
| App Key          | FAILED   | APP_KEY is missing         | Yes (doctor:repair)   |
| Storage Link     | FAILED   | Public symlink is missing  | Yes (doctor:repair)   |
| DB Connection    | PASSED   | Connected successfully     | -                     |
+------------------+----------+----------------------------+-----------------------+
```

---

## 🗺️ Roadmap

### Version 1 (Core)
- **Health Checks**: Cache, Storage, Database, Queue, Mail, App Key, Permissions, Config.
- **Repair Engine**: Auto-resolve common issues.
- **Reports**: Health Score & Summary generation.

### Version 2 (CI/CD & DevOps)
- Docker & CI workflow validation.
- Structured JSON reporting.
- GitHub Actions integration.

### Version 3 (Advanced Integration)
- Web Dashboard.
- Third-party Plugins.
- AWS, Nginx, Horizon, and Octane checkers.

---

## 🤝 Contributing

Thank you for considering contributing to Laravel Doctor! Please read our [Contributing Guide](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
