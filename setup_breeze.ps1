
Copy-Item -Path "routes/web.php" -Destination "routes/web.php.bak"
Copy-Item -Recurse -Path "resources/views/layouts" -Destination "resources/views/layouts.bak"
Copy-Item -Recurse -Path "resources/views/auth" -Destination "resources/views/auth.bak"
Copy-Item -Path "config/auth.php" -Destination "config/auth.php.bak"


Copy-Item -Recurse -Path "app/Http/Controllers/Auth" -Destination "app/Http/Controllers/Auth.bak" -ErrorAction SilentlyContinue


# php artisan breeze:install blade

# Restore custom files
Copy-Item -Path "routes/web.php.bak" -Destination "routes/web.php" -Force
Copy-Item -Recurse -Path "resources/views/layouts.bak\*" -Destination "resources/views/layouts" -Force
Copy-Item -Recurse -Path "resources/views/auth.bak\*" -Destination "resources/views/auth" -Force
Copy-Item -Path "config/auth.php.bak" -Destination "config/auth.php" -Force

# Restore Auth controllers (if applicable)
Copy-Item -Recurse -Path "app/Http/Controllers/Auth.bak\*" -Destination "app/Http/Controllers/Auth" -Force -ErrorAction SilentlyContinue

Clean up - remove backup files if needed
Remove-Item "routes/web.php.bak"
Remove-Item -Recurse "resources/views/layouts.bak"
Remove-Item -Recurse "resources/views/auth.bak"
Remove-Item "config/auth.php.bak"
Remove-Item -Recurse "app/Http/Controllers/Auth.bak"