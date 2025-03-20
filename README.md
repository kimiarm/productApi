# productApi
# Run The Project 


git clone https://github.com/your-repo/product-api.git
cd product-api
composer install
cp .env.example .env  # Configure DB (MongoDB or PostgreSQL)
docker-compose up --build -d  # Start services
composer start  # Run API (http://localhost:8000)


# Run Tests 

vendor/bin/phpunit  # Run all tests
vendor/bin/phpunit tests/Unit/ProductServiceTest.php  # Run specific test

