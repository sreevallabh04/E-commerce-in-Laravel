# LuxFurn - Premium Furniture E-commerce Platform

A modern, responsive luxury furniture e-commerce platform built with Laravel backend and vanilla JavaScript frontend.

## 🪑 Features

- **Modern UI/UX** - Beautiful, responsive design with dark/light theme
- **Product Management** - Full CRUD operations for products and categories
- **Shopping Cart** - Session-based cart with API integration
- **Search & Filter** - Advanced product search and filtering
- **Multi-language** - Support for English and Hindi
- **Voice Search** - Speech recognition for hands-free searching
- **Quick View** - Modal product previews
- **Responsive Design** - Mobile-first approach
- **API-First** - RESTful API for frontend-backend communication

## 🚀 Tech Stack

### Backend
- **Laravel 10** - PHP framework
- **SQLite** - Database (can be changed to MySQL/PostgreSQL)
- **Eloquent ORM** - Database management
- **RESTful API** - JSON API endpoints

### Frontend
- **Vanilla JavaScript** - No framework dependencies
- **CSS3** - Modern styling with CSS Grid and Flexbox
- **HTML5** - Semantic markup
- **Font Awesome** - Icons
- **Google Fonts** - Typography

## 📦 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & npm
- Git

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd luxury-furniture
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
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
   php artisan migrate:fresh --seed
   ```

6. **Start the development servers**
   ```bash
   # Terminal 1 - Laravel server
   php artisan serve
   
   # Terminal 2 - Vite dev server (optional)
   npm run dev
   ```

## 🌐 Access Points

- **Main Application**: `http://localhost:8000`
- **API Endpoints**: `http://localhost:8000/api/*`
- **Vite Dev Server**: `http://localhost:5173` (if running)

## 📚 API Endpoints

### Products
- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get single product
- `GET /api/categories` - List all categories

### Cart
- `GET /api/cart` - Get cart items
- `POST /api/cart/add` - Add item to cart
- `PUT /api/cart/update` - Update cart item
- `DELETE /api/cart/remove` - Remove item from cart
- `POST /api/cart/clear` - Clear cart

## 🗄️ Database Structure

### Categories
- id, name, slug, description, image, status, sort_order

### Products
- id, name, description, short_description, price, sale_price, sku, stock_quantity, in_stock, featured, weight, dimensions, material, color, status, category_id

## 🎨 Customization

### Themes
The application supports dark and light themes. Users can toggle between themes using the theme button in the navigation.

### Languages
Currently supports English and Hindi. Language files are located in `resources/lang/`.

### Styling
Main styles are in `public/css/styles.css`. The design uses CSS custom properties for easy theming.

## 🔧 Development

### Adding New Products
1. Use the database seeder: `php artisan db:seed --class=ProductSeeder`
2. Or add directly via API: `POST /api/products`

### Adding New Categories
1. Use the database seeder: `php artisan db:seed --class=CategorySeeder`
2. Or add directly via API: `POST /api/categories`

### Frontend Development
- Main JavaScript: `public/js/script.js`
- Main CSS: `public/css/styles.css`
- Blade template: `resources/views/frontend/index.blade.php`

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Set up proper database (MySQL/PostgreSQL)
5. Configure web server (Apache/Nginx)

### Environment Variables
- `APP_ENV` - Application environment
- `APP_DEBUG` - Debug mode
- `DB_CONNECTION` - Database connection
- `DB_DATABASE` - Database name
- `DB_USERNAME` - Database username
- `DB_PASSWORD` - Database password

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📞 Support

For support and questions, please open an issue in the GitHub repository.

---

**Built with ❤️ for luxury furniture enthusiasts** 