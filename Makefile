# Skill Graph Docker Management

.PHONY: help build up down restart logs shell mysql tinker migrate seed test npm-install npm-dev npm-build clean

# Default target
help:
	@echo "Available commands:"
	@echo "  build       - Build Docker containers"
	@echo "  up          - Start all services"
	@echo "  down        - Stop all services"
	@echo "  restart     - Restart all services"
	@echo "  logs        - View application logs"
	@echo "  shell       - Access application shell"
	@echo "  mysql       - Access MySQL shell"
	@echo "  tinker      - Start Laravel Tinker"
	@echo "  migrate     - Run database migrations"
	@echo "  seed        - Seed the database"
	@echo "  test        - Run tests"
	@echo "  npm-install - Install Node.js dependencies"
	@echo "  npm-dev     - Start Vite development server"
	@echo "  npm-build   - Build frontend assets"
	@echo "  clean       - Clean up Docker resources"

# Build containers
build:
	docker-compose build

# Start services
up:
	docker-compose up -d

# Stop services
down:
	docker-compose down

# Restart services
restart:
	docker-compose restart

# View logs
logs:
	docker-compose logs -f app

# Access application shell
shell:
	docker-compose exec app bash

# Access MySQL shell
mysql:
	docker-compose exec mysql mysql -u skill_graph_user -p skill_graph

# Start Laravel Tinker
tinker:
	docker-compose exec app php artisan tinker

# Run migrations
migrate:
	docker-compose exec app php artisan migrate

# Seed database
seed:
	docker-compose exec app php artisan db:seed

# Run tests
test:
	docker-compose exec app php artisan test

# Install Node.js dependencies
npm-install:
	docker-compose exec node npm install

# Start Vite development server
npm-dev:
	docker-compose exec node npm run dev

# Build frontend assets
npm-build:
	docker-compose exec node npm run build

# Clean up Docker resources
clean:
	docker-compose down -v
	docker system prune -f
	docker volume prune -f

# First-time setup
setup: build up migrate
	@echo "Setup complete! Application is available at http://localhost:8000" 