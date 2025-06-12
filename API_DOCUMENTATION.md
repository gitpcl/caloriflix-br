# Caloriflix API Documentation

This document provides comprehensive documentation for the Caloriflix API endpoints, including authentication, request/response formats, and examples.

## API Versioning

The Caloriflix API uses URL-based versioning to ensure backward compatibility as the API evolves. The current version is `v1`.

## Base URL

All API endpoints are relative to:
```
https://your-domain.com/api/v1/
```

### API Information

To get general API information and available versions:

```http
GET /api/
```

**Response:**
```json
{
  "name": "Caloriflix API",
  "version": "1.0.0",
  "current_time": "2025-05-28T23:18:43-04:00",
  "documentation": "https://your-domain.com/api/documentation"
}
```

## Authentication

The Caloriflix API uses Laravel Sanctum token-based authentication. 

### Obtaining a Token

```http
POST /api/login
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "your_password"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "token": "1|abcdefghijklmnopqrstuvwxyz123456789",
    "name": "User Name"
  },
  "message": "User logged in successfully."
}
```

### Using the Token

Include the token in all subsequent API requests in the Authorization header:

```http
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456789
```

### Registration

```http
POST /api/register
```

**Request Body:**
```json
{
  "name": "New User",
  "email": "newuser@example.com",
  "password": "secure_password",
  "password_confirmation": "secure_password"
}
```

### Logout

```http
POST /api/logout
```

## Foods API

### List Foods

```http
GET /api/foods
```

**Query Parameters:**
- `search`: Search term for food name
- `source`: Filter by source (`manual` or `whatsapp`)
- `favorite`: Filter by favorites (boolean)
- `sort_by`: Field to sort by (name, calories, protein, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "user_id": 1,
        "name": "Chicken Breast",
        "quantity": "100.00",
        "unit": "g",
        "protein": "31.00",
        "fat": "3.60",
        "carbohydrate": "0.00",
        "fiber": "0.00",
        "calories": "165.00",
        "barcode": null,
        "is_favorite": true,
        "source": "manual",
        "created_at": "2025-05-28T12:00:00.000000Z",
        "updated_at": "2025-05-28T12:00:00.000000Z"
      }
    ],
    "first_page_url": "...",
    "from": 1,
    "last_page": 5,
    "last_page_url": "...",
    "links": [...],
    "next_page_url": "...",
    "path": "...",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 72
  },
  "message": "Foods retrieved successfully"
}
```

### Create Food

```http
POST /api/foods
```

**Request Body:**
```json
{
  "name": "Oatmeal",
  "quantity": 100,
  "unit": "g",
  "protein": 13.15,
  "fat": 6.52,
  "carbohydrate": 68.18,
  "fiber": 10.1,
  "calories": 379,
  "barcode": "123456789",
  "is_favorite": false
}
```

### Get Food Details

```http
GET /api/foods/{id}
```

### Update Food

```http
PUT /api/foods/{id}
```

**Request Body:**
```json
{
  "name": "Oatmeal Updated",
  "quantity": 100,
  "unit": "g",
  "protein": 13.15,
  "fat": 6.52,
  "carbohydrate": 68.18,
  "fiber": 10.1,
  "calories": 379,
  "barcode": "123456789",
  "is_favorite": true
}
```

### Delete Food

```http
DELETE /api/foods/{id}
```

### Get Favorite Foods

```http
GET /api/foods/favorites
```

## Meals API

### List Meals

```http
GET /api/meals
```

**Query Parameters:**
- `start_date`: Start date for filtering (YYYY-MM-DD)
- `end_date`: End date for filtering (YYYY-MM-DD)
- `type`: Filter by meal type (breakfast, lunch, dinner, snack)
- `sort_by`: Field to sort by (date, type, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Meal

```http
POST /api/meals
```

**Request Body:**
```json
{
  "date": "2025-05-28",
  "type": "breakfast",
  "note": "Morning protein meal"
}
```

### Get Meal Details

```http
GET /api/meals/{id}
```

### Update Meal

```http
PUT /api/meals/{id}
```

**Request Body:**
```json
{
  "date": "2025-05-28",
  "type": "lunch",
  "note": "Updated note"
}
```

### Delete Meal

```http
DELETE /api/meals/{id}
```

### Get Today's Meals

```http
GET /api/meals/today
```

### Get Meals by Date

```http
GET /api/meals/date/{date}
```

## Meal Items API

### List Meal Items

```http
GET /api/meal-items
```

**Query Parameters:**
- `meal_id`: Filter by meal ID
- `per_page`: Number of records per page

### Create Meal Item

```http
POST /api/meal-items
```

**Request Body:**
```json
{
  "meal_id": 1,
  "food_id": 5,
  "quantity": 150,
  "notes": "With salt and pepper"
}
```

### Get Meal Item Details

```http
GET /api/meal-items/{id}
```

### Update Meal Item

```http
PUT /api/meal-items/{id}
```

**Request Body:**
```json
{
  "quantity": 200,
  "notes": "Updated notes"
}
```

### Delete Meal Item

```http
DELETE /api/meal-items/{id}
```

## Diary API

### List Diary Entries

```http
GET /api/diary
```

**Query Parameters:**
- `start_date`: Start date for filtering (YYYY-MM-DD)
- `end_date`: End date for filtering (YYYY-MM-DD)
- `sort_by`: Field to sort by (date, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Diary Entry

```http
POST /api/diary
```

**Request Body:**
```json
{
  "date": "2025-05-28",
  "water": 1500,
  "notes": "Feeling good today",
  "mood": "happy"
}
```

### Get Diary Entry

```http
GET /api/diary/{id}
```

### Update Diary Entry

```http
PUT /api/diary/{id}
```

**Request Body:**
```json
{
  "water": 2000,
  "notes": "Updated notes",
  "mood": "energetic"
}
```

### Delete Diary Entry

```http
DELETE /api/diary/{id}
```

### Get Diary by Date

```http
GET /api/diary/date/{date}
```

## Recipes API

### List Recipes

```http
GET /api/recipes
```

**Query Parameters:**
- `search`: Search term for recipe name
- `sort_by`: Field to sort by (name, preparation_time, cooking_time, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Recipe

```http
POST /api/recipes
```

**Request Body:**
```json
{
  "name": "Protein Pancakes",
  "ingredients": "2 eggs, 1 banana, 30g oats, 1 scoop protein powder",
  "instructions": "Blend all ingredients. Cook on a non-stick pan.",
  "preparation_time": 10,
  "cooking_time": 15,
  "servings": 2,
  "protein": 25,
  "fat": 10,
  "carbohydrate": 30,
  "fiber": 5,
  "calories": 320
}
```

### Get Recipe Details

```http
GET /api/recipes/{id}
```

### Update Recipe

```http
PUT /api/recipes/{id}
```

**Request Body:**
```json
{
  "name": "Updated Protein Pancakes",
  "instructions": "Updated instructions"
}
```

### Delete Recipe

```http
DELETE /api/recipes/{id}
```

## Measurements API

### List Measurements

```http
GET /api/measurements
```

**Query Parameters:**
- `start_date`: Start date for filtering (YYYY-MM-DD)
- `end_date`: End date for filtering (YYYY-MM-DD)
- `type`: Filter by measurement type (glucose, weight, blood_pressure, other)
- `sort_by`: Field to sort by (date, type, value, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Measurement

```http
POST /api/measurements
```

**Request Body:**
```json
{
  "date": "2025-05-28",
  "time": "07:30",
  "type": "glucose",
  "value": 95,
  "unit": "mg/dL",
  "notes": "Fasting measurement"
}
```

### Get Measurement Details

```http
GET /api/measurements/{id}
```

### Update Measurement

```http
PUT /api/measurements/{id}
```

**Request Body:**
```json
{
  "value": 98,
  "notes": "Updated notes"
}
```

### Delete Measurement

```http
DELETE /api/measurements/{id}
```

### Get Latest Measurements

```http
GET /api/measurements/latest
```

### Get Measurements by Type

```http
GET /api/measurements/type/{type}
```

**Supported Types:**
- `glucose`
- `weight`
- `blood_pressure`
- `other`

## Nutritional Goals API

### List Nutritional Goals

```http
GET /api/nutritional-goals
```

### Create Nutritional Goal

```http
POST /api/nutritional-goals
```

**Request Body:**
```json
{
  "calories": 2200,
  "protein": 150,
  "carbohydrate": 220,
  "fat": 70,
  "fiber": 30,
  "water": 2500,
  "start_date": "2025-06-01",
  "end_date": "2025-12-31",
  "is_active": true
}
```

### Get Nutritional Goal Details

```http
GET /api/nutritional-goals/{id}
```

### Update Nutritional Goal

```http
PUT /api/nutritional-goals/{id}
```

**Request Body:**
```json
{
  "calories": 2000,
  "protein": 180,
  "is_active": true
}
```

### Delete Nutritional Goal

```http
DELETE /api/nutritional-goals/{id}
```

### Get Current Nutritional Goal

```http
GET /api/nutritional-goals/current
```

## User Profile API

### Get User Profile

```http
GET /api/profile
```

### Update User Profile

```http
PUT /api/profile
```

**Request Body:**
```json
{
  "gender": "male",
  "birth_date": "1990-01-15",
  "height": 180,
  "weight": 75,
  "goal_weight": 70,
  "activity_level": "moderately_active",
  "diet_type": "balanced",
  "allergies": "nuts, shellfish",
  "bio": "Fitness enthusiast working on improving my diet"
}
```

## User Preferences API

### Get User Preferences

```http
GET /api/preferences
```

### Update User Preferences

```http
PUT /api/preferences
```

**Request Body:**
```json
{
  "theme": "dark",
  "language": "pt",
  "notifications_enabled": true,
  "email_notifications": false,
  "weekly_report": true,
  "meal_reminders": true,
  "water_reminders": true,
  "measurement_reminders": false,
  "display_units": "metric"
}
```

## Reminders API

### List Reminders

```http
GET /api/reminders
```

**Query Parameters:**
- `type`: Filter by reminder type (meal, water, medication, measurement, other)
- `active`: Filter by active status (boolean)
- `sort_by`: Field to sort by (title, type, time, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Reminder

```http
POST /api/reminders
```

**Request Body:**
```json
{
  "title": "Drink Water",
  "description": "Regular water reminder",
  "type": "water",
  "time": "14:00",
  "days": [0, 1, 2, 3, 4, 5, 6],
  "active": true,
  "repeat_type": "daily",
  "details": [
    {
      "content": "Drink at least 250ml of water",
      "order": 0
    },
    {
      "content": "Record in the water tracker",
      "order": 1
    }
  ]
}
```

### Get Reminder Details

```http
GET /api/reminders/{id}
```

### Update Reminder

```http
PUT /api/reminders/{id}
```

**Request Body:**
```json
{
  "title": "Updated Reminder",
  "time": "15:30",
  "active": false,
  "details": [
    {
      "id": 1,
      "content": "Updated content",
      "order": 0
    },
    {
      "content": "New step",
      "order": 1
    }
  ]
}
```

### Delete Reminder

```http
DELETE /api/reminders/{id}
```

## Reports API

### Get Default Reports (Daily)

```http
GET /api/reports
```

### Get Reports by Period

```http
GET /api/reports/period/{period}
```

**Supported Periods:**
- `daily`
- `weekly`
- `monthly`

### Get Custom Range Reports

```http
GET /api/reports/custom
```

**Query Parameters:**
- `start_date`: Start date (YYYY-MM-DD)
- `end_date`: End date (YYYY-MM-DD)

**Example Response:**
```json
{
  "success": true,
  "data": {
    "period_type": "weekly",
    "start_date": "2025-05-22",
    "end_date": "2025-05-28",
    "number_of_days": 7,
    "period_display": "Semana de 22/05/2025 até 28/05/2025",
    "nutrition": {
      "calories": {
        "total": 14285.5,
        "average": 2040.8
      },
      "macros": {
        "protein": {
          "total": 876.4,
          "average": 125.2,
          "percentage": 30
        },
        "carbohydrates": {
          "total": 1314.6,
          "average": 187.8,
          "percentage": 45
        },
        "fat": {
          "total": 730.3,
          "average": 104.3,
          "percentage": 25
        },
        "fiber": {
          "total": 175.0,
          "average": 25.0
        }
      }
    },
    "water": {
      "total": 12500,
      "average": 1785.7,
      "percentage": 89,
      "status": "good",
      "label": "Média diária de água (semana)"
    },
    "glucose": {
      "measurements": [...],
      "count": 14,
      "average": 98.5,
      "min": 85,
      "max": 118
    }
  },
  "message": "Reports data retrieved successfully"
}
```

## Error Handling

All API endpoints follow a consistent error response format:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": [
      "Validation error message"
    ]
  }
}
```

Common HTTP status codes:
- `200 OK`: Request successful
- `201 Created`: Resource created successfully
- `400 Bad Request`: Invalid request data
- `401 Unauthorized`: Authentication required or failed
- `403 Forbidden`: Authenticated but not authorized to access resource
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation errors
- `500 Server Error`: Something went wrong on the server

## Rate Limiting

The API implements tiered rate limiting to prevent abuse and ensure fair usage:

### Rate Limits by Endpoint Type

- **Authentication endpoints**: 5 requests per minute per IP
- **Authenticated general endpoints**: 100 requests per minute per user
- **Report endpoints**: 10 requests per minute per user (resource intensive)
- **Public endpoints**: 20 requests per minute per IP

### Rate Limit Headers

All responses include rate limiting headers:
- `X-RateLimit-Limit`: Maximum number of requests per minute
- `X-RateLimit-Remaining`: Number of requests remaining in the current window
- `X-RateLimit-Reset`: Time when the rate limit window resets (Unix timestamp)

### Rate Limit Response

When rate limits are exceeded (HTTP 429):
```json
{
  "success": false,
  "message": "Too many requests.",
  "error": "Rate limit exceeded. Please try again later.",
  "retry_after": 60
}
```

## Response Caching

The API implements intelligent caching to improve performance:

### Cache Headers

Cached responses include:
- `X-Cache-Status`: `HIT` or `MISS`
- `X-Cache-TTL`: Time to live in seconds

### Cache Duration by Resource

- **User profiles**: 2 hours
- **User preferences**: 2 hours  
- **Foods**: 1 hour
- **Meals**: 30 minutes
- **Reports**: 15 minutes
- **Measurements**: 30 minutes

### Cache Invalidation

Cache is automatically invalidated when:
- Resources are created, updated, or deleted
- Related resources are modified
- User profile/preferences change

## Token Management

### Token Expiration

All API tokens expire after 7 days by default. This can be configured per token.

### Token Management Endpoints

#### List Active Tokens
```http
GET /api/v1/tokens
```

#### Create New Token
```http
POST /api/v1/tokens
```

**Request Body:**
```json
{
  "name": "Mobile App Token",
  "abilities": ["read", "write"],
  "expires_in_days": 30
}
```

#### Refresh Current Token
```http
POST /api/v1/tokens/refresh
```

#### Revoke Specific Token
```http
DELETE /api/v1/tokens/{token_id}
```

#### Revoke All Other Tokens
```http
DELETE /api/v1/tokens/revoke-all
```

## API Security Enhancements

### CORS Configuration

The API includes secure CORS settings:
- Restricted to specific allowed origins
- Credentials support enabled for authenticated requests
- Exposed headers include rate limit information

### Input Validation

All endpoints use comprehensive validation:
- **Form Request classes** for structured validation
- **Custom validation messages** for better user experience
- **Type-safe validation** with proper casting
- **File and data size limits** to prevent abuse

### Error Handling

Consistent error responses across all endpoints:

#### Validation Errors (422)
```json
{
  "success": false,
  "message": "Validation Error.",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

#### Authentication Errors (401)
```json
{
  "success": false,
  "message": "Unauthenticated.",
  "error": "Please log in to access this resource."
}
```

#### Resource Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found.",
  "error": "Food not found."
}
```

#### Server Errors (500)
```json
{
  "success": false,
  "message": "Server Error.",
  "error": "Internal server error."
}
```

## Performance Optimizations

### Database Query Optimization

- **Query scopes** for reusable query logic
- **Eager loading** to prevent N+1 problems
- **Optimized indexes** for frequently queried fields
- **Pagination** for large datasets

### Response Format

All API responses follow a consistent structure:

#### Successful Response
```json
{
  "success": true,
  "data": { ... },
  "message": "Operation successful"
}
```

#### Paginated Response
```json
{
  "success": true,
  "data": [...],
  "message": "Data retrieved successfully",
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 15,
    "to": 15,
    "total": 72
  },
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  }
}
```

## API Testing

The API includes comprehensive test coverage:

- **Authentication tests** for login/logout flows
- **Validation tests** for all input scenarios
- **Rate limiting tests** to verify throttling
- **Exception handling tests** for error scenarios
- **Token management tests** for security features
- **Integration tests** for complete workflows

Run tests with:
```bash
php artisan test --testsuite=Feature
```

## Best Practices for API Usage

### Authentication
1. Store tokens securely (never in local storage)
2. Use HTTPS in production
3. Implement token refresh logic
4. Handle 401 responses gracefully

### Rate Limiting
1. Implement exponential backoff on 429 responses
2. Monitor rate limit headers
3. Cache responses when appropriate
4. Use pagination for large datasets

### Error Handling
1. Always check the `success` field
2. Display user-friendly error messages
3. Log detailed errors for debugging
4. Implement proper retry logic

### Performance
1. Use pagination parameters
2. Filter data at the API level
3. Cache static/semi-static data
4. Minimize unnecessary API calls
