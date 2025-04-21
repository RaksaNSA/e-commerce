# Use official PHP image with Apache
FROM php:8.2-apache

# Copy all project files into web server root
COPY . /var/www/html/

# Expose port 80 for web access
EXPOSE 80
