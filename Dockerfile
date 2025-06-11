FROM php:8.1-cli

# Set direktori kerja dalam container
WORKDIR /app

# Salin semua file dari project ke dalam container
COPY . .

# Jalankan PHP built-in server di port 8000
CMD ["php", "-S", "0.0.0.0:2083", "-t", "."]

# Buka port 8000
EXPOSE 2083