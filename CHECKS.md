# Laravel Doctor - Health Checks Specifications

This document defines every diagnostic check included in Version 1.

---

### 1. Storage Check (`StorageCheck`)
- **Problem**: Public storage symlink is missing.
- **Severity**: High
- **Reason**: Public uploads and assets will be unavailable, causing broken links for users.
- **Repair**: `storage:link`

---

### 2. App Key Check (`AppKeyCheck`)
- **Problem**: `APP_KEY` is missing or empty.
- **Severity**: Critical
- **Reason**: Security risk. Sessions, passwords, and other encrypted data cannot be decrypted or securely hashed.
- **Repair**: `key:generate`

---

### 3. Config Check (`ConfigCheck`)
- **Problem**: Configuration is not cached.
- **Severity**: Low
- **Reason**: Performance penalty. The application has to load and parse configuration files on every request.
- **Repair**: `config:cache`

---

### 4. Cache Check (`CacheCheck`)
- **Problem**: Cache driver is set to `file` in production or default store is unreachable.
- **Severity**: Medium
- **Reason**: Slower performance and scalability bottlenecks compared to Memcached or Redis in high-traffic settings.
- **Repair**: Suggest changing `CACHE_STORE` / `CACHE_DRIVER` in `.env` to `redis` or `memcached`.

---

### 5. Queue Check (`QueueCheck`)
- **Problem**: Queue driver is set to `sync` in production.
- **Severity**: High
- **Reason**: Degrades user experience by forcing background jobs (like emails) to run synchronously during HTTP requests.
- **Repair**: Suggest switching queue driver to `database`, `redis`, etc.

---

### 6. Mail Check (`MailCheck`)
- **Problem**: Mail driver is set to `log` in production.
- **Severity**: High
- **Reason**: Emails are not actually sent to users; they are only written to local logs.
- **Repair**: Warn and recommend production-ready mail providers (e.g., SMTP, Postmark, SES).

---

### 7. Database Check (`DatabaseCheck`)
- **Problem**: Database connection cannot be established or migrations are pending.
- **Severity**: Critical
- **Reason**: Complete application failure if the database is down, or runtime exceptions if database schemas are out of sync.
- **Repair**: `migrate` (if migrations are pending) or diagnostic verification of credentials.

---

### 8. Permission Check (`PermissionCheck`)
- **Problem**: Write permissions are missing for `storage` or `bootstrap/cache` directories.
- **Severity**: Critical
- **Reason**: Laravel requires write access to these directories to write session data, logs, compile views, and cache configuration.
- **Repair**: Suggest running permission corrections (e.g., `chmod -R 775` or changing owner/group to webserver user).
