#!/bin/bash

# Backup routes
cp routes/web.php routes/web.php.bak

# Backup layouts directory
cp -r resources/views/layouts resources/views/layouts.bak

# Backup auth views
cp -r resources/views/auth resources/views/auth.bak

# Backup dashboard view
cp resources/views/dashboard.blade.php resources/views/dashboard.blade.php.bak

# Backup auth controllers
cp -r app/Http/Controllers/Auth app/Http/Controllers/Auth.bak

# Backup DashboardController
cp app/Http/Controllers/DashboardController.php app/Http/Controllers/DashboardController.php.bak

# Backup config files related to auth (if customized)
cp config/auth.php config/auth.php.bak

# Install Breeze
php artisan breeze:install blade

# Restore routes
cp routes/web.php.bak routes/web.php

# Restore layouts directory
cp -r resources/views/layouts.bak/* resources/views/layouts

# Restore auth views
cp -r resources/views/auth.bak/* resources/views/auth

# Restore dashboard view
cp resources/views/dashboard.blade.php.bak resources/views/dashboard.blade.php

# Restore auth controllers
cp -r app/Http/Controllers/Auth.bak/* app/Http/Controllers/Auth

# Restore DashboardController
cp app/Http/Controllers/DashboardController.php.bak app/Http/Controllers/DashboardController.php

# Restore config files related to auth (if customized)
cp config/auth.php.bak config/auth.php
