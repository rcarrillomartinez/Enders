# Enders - Laravel Transfer Reservation System

A modern Laravel-based application for managing transfer reservations for hotels and travelers.

## Features

- **Multi-user Authentication**: Support for Travelers, Hotels, and Admin users
- **Transfer Reservations**: Create, view, update, and manage reservations
- **User Profiles**: Manage user information and preferences
- **Role-based Access Control**: Different views and permissions for different user types
- **Responsive UI**: Built with Bootstrap 5

## Technology Stack

- **Backend**: Laravel 11.x
- **Database**: MySQL 8.0
- **Frontend**: Blade Templates + Bootstrap 5
- **Server**: Nginx + PHP 8.2-FPM
- **Containerization**: Docker & Docker Compose
```

## Installation

### Using Docker (Recommended)

1. **Clone and navigate to project**:
   ```bash
   cd Enders-Laravel
   ```

2. **Build and start containers**:
   ```bash
   docker-compose up -d
   ```

3. **Install dependencies**:
   ```bash
   docker-compose exec app composer install
   ```

4. **Generate APP KEY**:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

5. **Run migrations**:
   ```bash
   docker-compose exec app php artisan migrate
   ```

6. **Access the application**:
   - Open browser to: http://localhost

### Local Development Setup

1. **Prerequisites**:
   - PHP 8.2+
   - Composer
   - MySQL 8.0+

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Create .env file**:
   ```bash
   cp .env.example .env
   ```

4. **Configure database** in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=enders_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate APP KEY**:
   ```bash
   php artisan key:generate
   ```

6. **Run migrations**:
   ```bash
   php artisan migrate
   ```

7. **Start the development server**:
   ```bash
   php artisan serve
   ```

8. **Access the application**:
   - Open browser to: http://localhost:8000

## Usage

### Authentication

1. **Register as Traveler**:
   - Click on "Register"
   - Select "Viajero" as user type
   - Fill in personal information
   - Create account

2. **Register as Hotel**:
   - Click on "Register"
   - Select "Hotel" as user type
   - Enter hotel name and credentials
   - Create account

3. **Login**:
   - Select your user type
   - Enter email/username and password

### Creating Reservations

1. Navigate to "Reservas"
2. Click "Nueva Reserva"
3. Fill in reservation details:
   - Client information
   - Hotel selection
   - Reservation type
   - Travel dates and flight information
   - Vehicle assignment (if applicable)
4. Submit form

### Admin Panel

Admins can:
- View all reservations
- Edit reservation details and status
- Delete reservations
- Manage reservation states (pending, confirmed, cancelled, completed)

## Database Schema

### Tables

- **transfer_admin**: Admin users
- **transfer_viajeros**: Traveler users
- **tranfer_hotel**: Hotel users
- **transfer_reservas**: Transfer reservations
- **transfer_vehiculo**: Available vehicles
- **tipo_reserva**: Reservation types
- **destinos**: Destination locations

## API Routes

### Authentication Routes
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout

### Profile Routes
- `GET /profile` - View user profile
- `PUT /profile` - Update profile

### Reservations Routes
- `GET /reservas` - List all reservations
- `GET /reservas/create` - Show create form
- `POST /reservas` - Store new reservation
- `GET /reservas/{id}` - Show reservation details
- `GET /reservas/{id}/edit` - Show edit form (admin only)
- `PUT /reservas/{id}` - Update reservation (admin only)
- `DELETE /reservas/{id}` - Delete reservation (admin only)

## Docker Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f app

# Execute artisan command
docker-compose exec app php artisan <command>

# Access database
docker-compose exec db mysql -u wordpress6 -pZApzis0YqfKOY2Pw enders_db
```

## Development

### Create new Model with Migration
```bash
php artisan make:model ModelName -m
```

### Create new Controller
```bash
php artisan make:controller ControllerName
```

### Create new Migration
```bash
php artisan make:migration create_table_name
```

### Seed Database
```bash
php artisan db:seed
```

## Configuration

Key configuration files:

- **config/cache.php** - Application settings
- **config/database.php** - Database configuration
- **config/auth.php** - Authentication guards and providers
- **.env** - Environment variables

## Troubleshooting

### Database Connection Issues
- Verify `.env` database credentials
- Ensure database server is running
- Check database existence

### Permission Errors (Docker)
```bash
docker-compose exec app chown -R appuser:appuser /var/www/enders
```

### Clear Cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

## Security Notes

- Always use strong passwords
- Never commit `.env` file to version control
- Keep Laravel and dependencies updated
- Use HTTPS in production
- Implement rate limiting for login endpoints

## Performance Optimization

- Enable opcache in production
- Use MySQL query caching
- Implement database indexing on frequently searched columns
- Cache API responses where applicable

## Contributing

1. Create feature branch: `git checkout -b feature/AmazingFeature`
2. Commit changes: `git commit -m 'Add AmazingFeature'`
3. Push to branch: `git push origin feature/AmazingFeature`
4. Open Pull Request

## License

This project is licensed under the MIT License - see LICENSE file for details.

## Support

For issues or questions, please contact the development team or create an issue in the repository.

---

**Last Updated**: December 2024
**Laravel Version**: 11.x
**PHP Version**: 8.2+
