# Use the official PHP image
FROM php:8.2-cli

# Set the working directory
WORKDIR /app

# Copy the PHP files into the container
COPY . .

# Expose port 8000
EXPOSE 8000

# Start the PHP development server
CMD ["php", "-S", "0.0.0.0:8000"]
