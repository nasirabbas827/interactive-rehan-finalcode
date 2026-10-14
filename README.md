# Entrepreneur Portal - Home

interactive-rehan-finalcode is an entrepreneur portal with business resources and community features.

![PHP](https://img.shields.io/badge/PHP-2563EB?style=flat-square) ![MySQL](https://img.shields.io/badge/MySQL-0F172A?style=flat-square)

## Overview

The data model covers `agents`, `categories`, `content`, `feedback`, `forum_posts`, `forum_topics`, `messages`, `resources`, `users`.

## Application areas

| Area | Pages in the repository |
| --- | --- |
| Admin | [`admin/dashboard-admin.php`](admin/dashboard-admin.php), [`admin/forum-view.php`](admin/forum-view.php), [`admin/forum.php`](admin/forum.php), [`admin/manage-agents.php`](admin/manage-agents.php), [`admin/manage-content.php`](admin/manage-content.php) |
| Agent | [`agent/agent-feedback.php`](agent/agent-feedback.php), [`agent/agent-profile.php`](agent/agent-profile.php), [`agent/dashboard-agent.php`](agent/dashboard-agent.php), [`agent/forum-view.php`](agent/forum-view.php), [`agent/forum.php`](agent/forum.php) |
| Root Pages | [`forum.php`](forum.php), [`login.php`](login.php), [`register.php`](register.php), [`resources.php`](resources.php), [`view-mentors.php`](view-mentors.php) |
| User | [`user/dashboard-user.php`](user/dashboard-user.php), [`user/forum-view.php`](user/forum-view.php), [`user/forum.php`](user/forum.php), [`user/send-message-process.php`](user/send-message-process.php), [`user/submit-feedback.php`](user/submit-feedback.php) |

## Getting started

### 1. Get the project

```bash
git clone https://github.com/nasirabbas827/interactive-rehan-finalcode.git
cd interactive-rehan-finalcode
```

### 2. Configure PHP and the database

1. Use a local PHP server with MySQL or MariaDB, such as XAMPP.
2. Import [`DB/entrepreneur_portal-rehan.sql`](DB/entrepreneur_portal-rehan.sql) into your local database server.
3. Set your local database connection details in [`admin/config.php`](admin/config.php), [`agent/config.php`](agent/config.php), [`config.php`](config.php), [`user/config.php`](user/config.php).
4. Keep the existing asset and upload directory paths intact.

### 3. Start the application

With the database running, start a local development server from the repository root:

```bash
php -S 127.0.0.1:8000
```

Open http://127.0.0.1:8000/index.php. If using Apache/XAMPP instead, place the project in its document root and open the matching localhost folder.

## Repository guide

| Path | Purpose |
| --- | --- |
| [`DB`](DB) | Database scripts |
| [`config.php`](config.php) | Application/database configuration |
| [`index.php`](index.php) | Web entry page |

## Development notes

These setup instructions are based on the repository source. The application has not been executed as part of this documentation update; verify dependencies and configuration in your local environment.
