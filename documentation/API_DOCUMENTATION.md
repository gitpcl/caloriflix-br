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
GET /api/v1/
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
POST /api/v1/login
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
POST /api/v1/register
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
POST /api/v1/logout
```

### Get Current User

```http
GET /api/v1/user
```

**Response:**
```json
{
  "id": 1,
  "name": "User Name",
  "email": "user@example.com",
  "email_verified_at": "2025-05-28T12:00:00.000000Z",
  "created_at": "2025-05-28T12:00:00.000000Z",
  "updated_at": "2025-05-28T12:00:00.000000Z"
}
```

## Foods API

### List Foods

```http
GET /api/v1/foods
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
POST /api/v1/foods
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
GET /api/v1/foods/{food}
```

### Update Food

```http
PUT /api/v1/foods/{food}
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
DELETE /api/v1/foods/{food}
```

### Get Favorite Foods

```http
GET /api/v1/foods/favorites
```

## Meals API

### List Meals

```http
GET /api/v1/meals
```

**Query Parameters:**
- `start_date`: Start date for filtering (YYYY-MM-DD)
- `end_date`: End date for filtering (YYYY-MM-DD)
- `type`: Filter by meal type (cafe_da_manha, almoco, lanche_da_tarde, jantar)
- `sort_by`: Field to sort by (date, type, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Meal

```http
POST /api/v1/meals
```

**Request Body:**
```json
{
  "meal_date": "2025-05-28",
  "meal_type": "cafe_da_manha",
  "notes": "Morning protein meal"
}
```

### Get Meal Details

```http
GET /api/v1/meals/{meal}
```

### Update Meal

```http
PUT /api/v1/meals/{meal}
```

**Request Body:**
```json
{
  "meal_date": "2025-05-28",
  "meal_type": "almoco",
  "notes": "Updated note"
}
```

### Delete Meal

```http
DELETE /api/v1/meals/{meal}
```

### Get Today's Meals

```http
GET /api/v1/meals/today
```

### Get Meals by Date

```http
GET /api/v1/meals/date/{date}
```

## Meal Items API

### List Meal Items

```http
GET /api/v1/meal-items
```

**Query Parameters:**
- `meal_id`: Filter by meal ID
- `per_page`: Number of records per page

### Create Meal Item

```http
POST /api/v1/meal-items
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
GET /api/v1/meal-items/{meal_item}
```

### Update Meal Item

```http
PUT /api/v1/meal-items/{meal_item}
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
DELETE /api/v1/meal-items/{meal_item}
```

## Diary API

### List Diary Entries

```http
GET /api/v1/diary
```

**Query Parameters:**
- `start_date`: Start date for filtering (YYYY-MM-DD)
- `end_date`: End date for filtering (YYYY-MM-DD)
- `sort_by`: Field to sort by (date, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Diary Entry

**Note:** `mood` is an integer from 1-5, and `sleep` is in minutes.

```http
POST /api/v1/diary
```

**Request Body:**
```json
{
  "date": "2025-05-28",
  "water": 1500,
  "notes": "Feeling good today",
  "mood": 4,
  "sleep": 480
}
```

### Get Diary Entry

```http
GET /api/v1/diary/{diary}
```

### Update Diary Entry

```http
PUT /api/v1/diary/{diary}
```

**Request Body:**
```json
{
  "water": 2000,
  "notes": "Updated notes",
  "mood": 5,
  "sleep": 420
}
```

### Delete Diary Entry

```http
DELETE /api/v1/diary/{diary}
```

### Get Diary by Date

```http
GET /api/v1/diary/date/{date}
```

## Recipes API

### List Recipes

```http
GET /api/v1/recipes
```

**Query Parameters:**
- `search`: Search term for recipe name
- `sort_by`: Field to sort by (name, preparation_time, cooking_time, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Recipe

```http
POST /api/v1/recipes
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
GET /api/v1/recipes/{recipe}
```

### Update Recipe

```http
PUT /api/v1/recipes/{recipe}
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
DELETE /api/v1/recipes/{recipe}
```

## Measurements API

### List Measurements

```http
GET /api/v1/measurements
```

**Query Parameters:**
- `start_date`: Start date for filtering (YYYY-MM-DD)
- `end_date`: End date for filtering (YYYY-MM-DD)
- `type`: Filter by measurement type (weight, body_fat, lean_mass, arm, forearm, waist, hip, thigh, calf, bmr, body_water)
- `sort_by`: Field to sort by (date, type, value, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Measurement

```http
POST /api/v1/measurements
```

**Request Body:**
```json
{
  "date": "2025-05-28",
  "type": "weight",
  "value": 75.5,
  "notes": "Morning weight"
}
```

### Get Measurement Details

```http
GET /api/v1/measurements/{measurement}
```

### Update Measurement

```http
PUT /api/v1/measurements/{measurement}
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
DELETE /api/v1/measurements/{measurement}
```

### Get Latest Measurements

```http
GET /api/v1/measurements/latest
```

### Get Measurements by Type

```http
GET /api/v1/measurements/type/{type}
```

**Supported Types:**
- `weight`
- `body_fat`
- `lean_mass`
- `arm`
- `forearm`
- `waist`
- `hip`
- `thigh`
- `calf`
- `bmr`
- `body_water`

## Nutritional Goals API

### List Nutritional Goals

```http
GET /api/v1/nutritional-goals
```

### Create Nutritional Goal

```http
POST /api/v1/nutritional-goals
```

**Request Body:**
```json
{
  "protein": 150,
  "carbs": 220,
  "fat": 70,
  "fiber": 30,
  "calories": 2200,
  "water": 2500,
  "objective": "weight loss"
}
```

### Get Nutritional Goal Details

```http
GET /api/v1/nutritional-goals/{nutritional_goal}
```

### Update Nutritional Goal

```http
PUT /api/v1/nutritional-goals/{nutritional_goal}
```

**Request Body:**
```json
{
  "calories": 2000,
  "protein": 180,
  "carbs": 200,
  "objective": "maintenance"
}
```

### Delete Nutritional Goal

```http
DELETE /api/v1/nutritional-goals/{nutritional_goal}
```

### Get Current Nutritional Goal

```http
GET /api/v1/nutritional-goals/current
```

## User Profile API

### Get User Profile

```http
GET /api/v1/profile
```

### Update User Profile

```http
PUT /api/v1/profile
```

**Request Body:**
```json
{
  "weight": 75.5,
  "height": 180,
  "gender": "male",
  "age": 35,
  "activity_level": "moderately_active",
  "basal_metabolic_rate": 1750,
  "use_basal_metabolic_rate": true
}
```

## User Preferences API

### Get User Preferences

```http
GET /api/v1/preferences
```

### Update User Preferences

```http
PUT /api/v1/preferences
```

**Request Body:**
```json
{
  "glycemic_index_enabled": false,
  "cholesterol_enabled": false,
  "keto_diet_enabled": false,
  "paleo_diet_enabled": false,
  "low_fodmap_enabled": false,
  "low_carb_enabled": false,
  "meal_plan_evaluation_enabled": false,
  "time_zone": "UTC-3",
  "silent_mode_enabled": false,
  "language": "Português",
  "prioritize_taco_enabled": false,
  "daily_log_enabled": true,
  "photo_with_macros_enabled": false,
  "auto_fasting_enabled": true,
  "detailed_foods_enabled": false,
  "show_dashboard_enabled": false,
  "advanced_food_analysis_enabled": false,
  "group_water_enabled": false,
  "expanded_sections": {"diet_features": true}
}
```

## Reminders API

### List Reminders

```http
GET /api/v1/reminders
```

**Query Parameters:**
- `active`: Filter by active status (boolean)
- `sort_by`: Field to sort by (name, start_time, created_at)
- `sort_direction`: Sort direction (asc, desc)
- `per_page`: Number of records per page

### Create Reminder

```http
POST /api/v1/reminders
```

**Request Body:**
```json
{
  "name": "Drink Water",
  "description": "Regular water reminder",
  "reminder_type": "intervalo de tempo",
  "interval_hours": 2,
  "interval_minutes": 0,
  "start_time": "08:00:00",
  "end_time": "22:00:00",
  "buttons_enabled": true,
  "auto_command_enabled": false,
  "auto_command": null,
  "active": true,
  "reminder_details": [
    {
      "button_text": "250ml",
      "button_action": "/water 250",
      "display_order": 0
    },
    {
      "button_text": "500ml",
      "button_action": "/water 500",
      "display_order": 1
    }
  ]
}
```

### Get Reminder Details

```http
GET /api/v1/reminders/{reminder}
```

### Update Reminder

```http
PUT /api/v1/reminders/{reminder}
```

**Request Body:**
```json
{
  "name": "Updated Reminder",
  "interval_hours": 3,
  "active": false,
  "reminder_details": [
    {
      "id": 1,
      "button_text": "Updated button",
      "button_action": "/water 300",
      "display_order": 0
    },
    {
      "button_text": "New button",
      "button_action": "/water 750",
      "display_order": 1
    }
  ]
}
```

### Delete Reminder

```http
DELETE /api/v1/reminders/{reminder}
```

## Reports API

### Get Default Reports (Daily)

```http
GET /api/v1/reports
```

### Get Reports by Period

```http
GET /api/v1/reports/period/{period}
```

**Supported Periods:**
- `daily`
- `weekly`
- `monthly`

### Get Custom Range Reports

```http
GET /api/v1/reports/custom
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

### Cache Duration by Endpoint

Based on the implemented middleware:
- **GET /api/v1/foods/favorites**: 30 minutes
- **GET /api/v1/foods**: 15 minutes
- **GET /api/v1/foods/{food}**: 60 minutes
- **GET /api/v1/profile**: 120 minutes (2 hours)
- **GET /api/v1/preferences**: 120 minutes (2 hours)

### Cache Invalidation

Cache is automatically invalidated when:
- Resources are created, updated, or deleted
- Related resources are modified
- User profile/preferences change

The following endpoints trigger cache invalidation:
- **POST/PUT/DELETE /api/v1/foods**: Invalidates 'foods' cache
- **PUT /api/v1/profile**: Invalidates 'profile' and 'preferences' cache
- **PUT /api/v1/preferences**: Invalidates 'preferences' cache

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
DELETE /api/v1/tokens/{token}
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
