# BasicBot - Simple AI Chatbot

A lightweight, basic AI chatbot implementation using PHP and MySQL with OpenAI GPT integration.

## 📁 Project Structure

```
basicBot/
├── chat.php           # Main chat API endpoint
├── index.html         # Simple chat interface (if exists)
├── config.php         # Configuration file (if exists)
└── README.md         # This file
```

## 🚀 Features

- **Simple Chat Interface** - Basic HTML/CSS/JavaScript chat UI
- **OpenAI Integration** - Powered by GPT AI models
- **MySQL Database** - Stores chat history and user data
- **Product Search** - Basic product information retrieval
- **CORS Support** - Cross-origin requests enabled

## 🛠️ Setup Instructions

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- XAMPP/WAMP/LAMP server
- OpenAI API key

### Installation Steps

1. **Database Setup**
   ```sql
   CREATE DATABASE gpt_webapp;
   USE gpt_webapp;
   
   CREATE TABLE chat_history (
       id BIGINT AUTO_INCREMENT PRIMARY KEY,
       user VARCHAR(100),
       message TEXT,
       response TEXT,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   
   CREATE TABLE products (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(150),
       category VARCHAR(100),
       price DECIMAL(10,2),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

2. **Configuration**
   - Update database credentials in `chat.php`
   - Add your OpenAI API key in the `$api_key` variable
   - Ensure proper file permissions

3. **File Placement**
   - Place all files in your web server directory
   - Ensure `chat.php` is accessible via HTTP

## 🔧 Configuration

### Database Connection
Update the MySQL connection parameters in `chat.php`:
```php
$mysqli = new mysqli("localhost", "root", "", "gpt_webapp");
```

### OpenAI API Key
Add your OpenAI API key:
```php
$api_key = "your-openai-api-key-here";
```

### CORS Settings
Modify CORS headers if needed:
```php
header("Access-Control-Allow-Origin: *");
```

## 📡 API Usage

### Endpoint: `chat.php`

**Method:** POST  
**Content-Type:** application/json

**Request Body:**
```json
{
    "user": "Guest",
    "message": "Hello, how are you?"
}
```

**Response:**
```json
{
    "reply": "Hello! I'm doing well, thank you for asking. How can I help you today?"
}
```

### Error Responses
```json
{
    "error": "No message provided"
}
```

## 🎯 Features Breakdown

### 1. Chat Processing
- Receives user messages via POST request
- Processes messages through OpenAI GPT API
- Returns AI-generated responses

### 2. Product Search
The bot can detect product-related queries and search a local database:
- Keywords: "product", "show me", "price"
- Filters: category, price range
- Returns formatted product listings

### 3. Chat History
All conversations are automatically saved to the database for:
- Analytics and reporting
- Conversation continuity
- User behavior tracking

## 🔒 Security Considerations

⚠️ **Important Security Notes:**

1. **API Key Exposure**: The OpenAI API key is hardcoded - move to environment variables
2. **SQL Injection**: Use prepared statements for all database queries
3. **Input Validation**: Implement proper input sanitization
4. **CORS Policy**: Restrict origins in production environments
5. **Error Handling**: Avoid exposing sensitive information in error messages

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Check MySQL server status
   - Verify database credentials
   - Ensure database exists

2. **OpenAI API Errors**
   - Verify API key validity
   - Check API rate limits
   - Ensure internet connectivity

3. **CORS Errors**
   - Check browser console for CORS issues
   - Verify Access-Control headers
   - Test from same domain

### Error Codes
- `400`: Bad Request - Missing message
- `500`: Internal Server Error - Database/API issues

## 🔄 Upgrading

To upgrade to the full-featured version in the parent directory:

1. Copy user data from `chat_history` table
2. Update database schema to match new structure
3. Migrate to the enhanced version with user registration

## 📝 Development Notes

### Code Structure
- **Procedural PHP** - Simple, straightforward approach
- **Inline SQL** - Direct database queries (consider using prepared statements)
- **Basic Error Handling** - Minimal error checking (enhance for production)

### Performance
- No caching implemented
- Direct API calls to OpenAI
- Simple MySQL queries without optimization

### Limitations
- No user authentication
- Basic product search functionality
- Limited error handling
- Hardcoded configuration values

## 🤝 Contributing

This is a basic implementation. For contributions:
1. Focus on security improvements
2. Add input validation
3. Implement proper error handling
4. Add configuration management

## 📄 License

This is a basic educational implementation. Use at your own risk and ensure proper security measures before deployment.

## 🆘 Support

For issues related to this basic implementation:
1. Check the troubleshooting section
2. Verify your environment setup
3. Review the security considerations
4. Consider upgrading to the full-featured version

---

**Note**: This is a simplified version. For production use, consider the enhanced version in the parent directory with proper security, user management, and error handling.