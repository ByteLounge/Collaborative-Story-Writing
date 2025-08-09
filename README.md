# Collaborative Story Writing Platform

A simple and elegant collaborative story writing platform built with PHP, MySQL, HTML, and CSS. Users can create stories and contribute to them sequentially, building narratives together.

## Features

- **Story Creation**: Create new stories with titles
- **Collaborative Writing**: Add contributions to existing stories
- **Author Highlighting**: Different authors are highlighted with distinct colors
- **Responsive Design**: Works on desktop and mobile devices
- **Input Validation**: Prevents empty submissions and validates data
- **SQL Injection Protection**: Uses prepared statements for security
- **Clean UI**: Modern, gradient-based design with smooth animations

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or XAMPP/WAMP/MAMP
- PDO extension enabled

## Installation

### **Option 1: Local Development (XAMPP/WAMP)**

#### 1. Database Setup
1. Open your MySQL client (phpMyAdmin, MySQL Workbench, or command line)
2. Import the `database.sql` file to create the database and tables
3. The database will be created with sample data

#### 2. Configuration
1. Edit `config/database.php` to match your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'collaborative_story_db');
   define('DB_USER', 'root');  // Your MySQL username
   define('DB_PASS', '');      // Your MySQL password
   ```

#### 3. Web Server Setup
1. Place all files in your web server directory (e.g., `htdocs` for XAMPP)
2. Ensure your web server can access the files
3. Make sure PHP is properly configured

#### 4. Access the Application
1. Start your web server and MySQL service
2. Navigate to `http://localhost/collaborative-story-platform/`
3. The application should load with sample stories

### **Option 2: Free Online Hosting (Glitch)**

For free online hosting, see the complete guide in `GLITCH_DEPLOYMENT.md`:

1. **Quick Deploy:**
   - Upload your project to GitHub
   - Import to Glitch: [glitch.com](https://glitch.com)
   - Your app will be live at: `https://your-project-name.glitch.me`

2. **Features on Glitch:**
   - ✅ Free hosting with HTTPS
   - ✅ Free MySQL database
   - ✅ Automatic deployments
   - ✅ Real-time collaboration

3. **Test Your Deployment:**
   - Visit `/test.php` to verify everything works
   - Check logs for any issues

## File Structure

```
collaborative-story-platform/
├── config/
│   └── database.php          # Database connection and helper functions
├── assets/
│   └── css/
│       └── style.css         # Main stylesheet
├── database.sql              # Database schema and sample data
├── index.php                 # Homepage - list stories and create new ones
├── story.php                 # Story detail page - view and add contributions
└── README.md                 # This file
```

## Database Schema

### Stories Table
- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `title` (VARCHAR(255), NOT NULL)
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)

### Contributions Table
- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `story_id` (INT, NOT NULL, FOREIGN KEY)
- `author_name` (VARCHAR(100), NOT NULL)
- `content` (TEXT, NOT NULL)
- `contributed_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)

## Usage

### Creating a Story
1. Visit the homepage
2. Enter a story title in the "Create a New Story" form
3. Click "Create Story"
4. You'll be redirected to the story page

### Adding Contributions
1. Click on any story from the homepage
2. Fill in your name and contribution text
3. Click "Add Contribution"
4. Your contribution will be added to the story

### Viewing Stories
- All stories are listed on the homepage with creation dates
- Click any story to view all contributions in chronological order
- Each author's contributions are highlighted with different colors

## Security Features

- **Prepared Statements**: All database queries use prepared statements to prevent SQL injection
- **Input Sanitization**: All user input is sanitized using `htmlspecialchars()`
- **Input Validation**: Required fields are validated before processing
- **Error Handling**: Comprehensive error handling for database operations

## Customization

### Styling
- Edit `assets/css/style.css` to customize the appearance
- The design uses CSS Grid and Flexbox for responsive layouts
- Color schemes can be modified in the CSS variables

### Database
- Modify `database.sql` to add more sample data
- Adjust table structures in the SQL file if needed

### Features
- Add user authentication by modifying the PHP files
- Implement story categories by adding a `category` field to the stories table
- Add rich text editing by integrating a WYSIWYG editor

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check your database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify the database exists

2. **Page Not Found**
   - Ensure files are in the correct web server directory
   - Check file permissions
   - Verify URL path is correct

3. **Form Not Working**
   - Check PHP error logs
   - Ensure all required fields are filled
   - Verify database tables exist

### Error Logs
- Check your web server's error logs for detailed error messages
- Enable PHP error reporting for debugging

## Browser Support

- Chrome (recommended)
- Firefox
- Safari
- Edge
- Mobile browsers

## License

This project is open source and available under the MIT License.

## Contributing

Feel free to submit issues and enhancement requests!
