# Skill Graph

A web application for managing skills and learning activities with an interactive visual graph interface. Track your learning journey, organize activities by skills, and visualize dependencies between different learning resources.

## ✨ Features

### 🎯 Core Functionality
- **Skill Management**: Create, edit, and organize skills with custom colors and descriptions
- **Activity Tracking**: Manage learning activities (courses, projects, books, certifications, etc.)
- **Visual Graph Interface**: Interactive drag-and-drop skill graph with React Flow
- **Dependency Management**: Define prerequisites and learning paths between activities
- **Progress Tracking**: Monitor activity status and learning progress

## 🛠️ Tech Stack

### Backend
- **Laravel 12**: Modern PHP framework with latest features
- **MySQL/SQLite**: Database with Eloquent ORM
- **Inertia.js**: Full-stack framework bridging Laravel and React
- **Spatie Permissions**: Role and permission management

### Frontend
- **React 18**: Modern React with hooks and TypeScript
- **TypeScript**: Type-safe development
- **Tailwind CSS**: Utility-first CSS framework
- **React Flow**: Interactive graph visualization
- **Shadcn/UI**: Modern component library
- **Lucide Icons**: Beautiful icon set

### Development Tools
- **Vite**: Fast build tool and development server
- **Laravel Breeze**: Authentication scaffolding
- **Docker Compose**: Containerized development environment
- **Pest**: Modern PHP testing framework

## 🚀 Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- npm/yarn
- Docker (optional, for containerized development)

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/skill-graph.git
   cd skill-graph
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start development servers**
   ```bash
   # Terminal 1: Laravel development server
   php artisan serve

   # Terminal 2: Vite development server
   npm run dev
   ```

7. **Access the application**
   - Open http://localhost:8000 in your browser
   - Register a new account or use seeded credentials

### Docker Development (Recommended)

The application includes a complete Docker environment with MySQL database:

```bash
# Clone the repository
git clone https://github.com/yourusername/skill-graph.git
cd skill-graph

# Copy Docker environment file
cp .env.docker .env

# Build and start all services
docker-compose up -d --build

# The application will be available at:
# - App: http://localhost:8000
# - PhpMyAdmin: http://localhost:8080
# - Vite Dev Server: http://localhost:5173
```

**Docker Services:**
- **app**: Laravel application (PHP 8.2, Nginx, Supervisor)
- **mysql**: MySQL 8.0 database
- **phpmyadmin**: Database management interface
- **node**: Node.js for frontend development (Vite)

**Docker Commands:**
```bash
# Using Makefile (recommended)
make help        # Show all available commands
make setup       # First-time setup (build, up, migrate)
make up          # Start all services
make down        # Stop all services
make logs        # View application logs
make shell       # Access application shell
make migrate     # Run database migrations
make test        # Run tests

# Using docker-compose directly
docker-compose logs -f app
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker
docker-compose exec node npm run build
docker-compose down
docker-compose up -d --build
```

## 📱 Usage

### Getting Started
1. **Register an Account**: Create your personal account
2. **Create Skills**: Add skills you want to learn (e.g., "React", "Laravel", "Machine Learning")
3. **Add Activities**: Create learning activities within each skill
4. **Set Dependencies**: Define prerequisites between activities
5. **Visualize Progress**: Use the skill graph to see your learning journey

### Skill Management
- **Create Skills**: Add new skills with names, descriptions, and custom colors
- **Edit Skills**: Update skill information and visual appearance
- **Delete Skills**: Remove skills and their associated activities

### Activity Management
- **Activity Types**: Courses, Projects, Books, Practice, Certifications, Other
- **Status Tracking**: Not Started, In Progress, Completed, Paused
- **Time Estimation**: Set estimated and actual hours
- **Dependencies**: Link activities to create learning paths
- **Metadata**: Store additional information in JSON format

### Visual Graph Interface
- **Interactive Canvas**: Full-screen drag-and-drop interface
- **Skill Nodes**: Visual representation of skills as root nodes
- **Activity Connections**: Dependencies shown as connecting lines
- **Real-time Updates**: Changes reflect immediately across all views
- **Persistent Positions**: Node positions saved automatically

## 🏗️ Project Structure

```
skill-graph/
├── app/
│   ├── Http/Controllers/     # API Controllers
│   ├── Models/              # Eloquent Models
│   └── Policies/            # Authorization Policies
├── database/
│   ├── migrations/          # Database Migrations
│   └── factories/           # Model Factories
├── resources/
│   ├── js/
│   │   ├── components/      # React Components
│   │   ├── pages/          # Page Components
│   │   └── types/          # TypeScript Types
│   └── views/              # Blade Templates
├── routes/
│   ├── web.php             # Web Routes
│   └── auth.php            # Authentication Routes
└── tests/                  # Test Files
```

## 🧪 Testing

Run the test suite:

```bash
# PHP tests
php artisan test

# JavaScript tests (if configured)
npm run test
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards for PHP
- Use TypeScript for all new frontend code
- Write tests for new features
- Update documentation as needed
- Ensure all tests pass before submitting

## 📋 Requirements

- PHP 8.2 or higher
- Laravel 11+
- Node.js 18+
- MySQL 8.0+ or SQLite 3.35+
- Composer 2.0+

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.