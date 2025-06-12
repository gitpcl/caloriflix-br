# Caloriflix Database Schema

## Overview
Caloriflix is a comprehensive nutrition tracking application built with Laravel. This document outlines the complete database structure, including all tables, their relationships, and key constraints.

## Database Tables and Relationships

### 1. Authentication & System Tables

#### users
The core user authentication table.
- `id` (bigint, primary key)
- `name` (string)
- `email` (string, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `remember_token` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### password_reset_tokens
Stores password reset tokens for users.
- `email` (string, primary key)
- `token` (string)
- `created_at` (timestamp, nullable)

#### sessions
Manages user sessions.
- `id` (string, primary key)
- `user_id` (foreignId, nullable) → **references** `users.id`
- `ip_address` (string, 45 chars, nullable)
- `user_agent` (text, nullable)
- `payload` (longText)
- `last_activity` (integer, indexed)

#### personal_access_tokens
Laravel Sanctum tokens for API authentication.
- `id` (bigint, primary key)
- `tokenable_type` (string) - polymorphic relationship
- `tokenable_id` (bigint) - polymorphic relationship
- `name` (string)
- `token` (string, 64 chars, unique)
- `abilities` (text, nullable)
- `last_used_at` (timestamp, nullable)
- `expires_at` (timestamp, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

### 2. Core Nutrition Tables

#### meals
Stores user meals organized by meal type and date.
- `id` (bigint, primary key)
- `user_id` (foreignId) → **references** `users.id` (cascade delete)
- `meal_type` (enum: 'cafe_da_manha', 'almoco', 'lanche_da_tarde', 'jantar')
- `meal_date` (date)
- `notes` (text, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### foods
Master food database containing nutritional information.
- `id` (bigint, primary key)
- `user_id` (foreignId, nullable) → **references** `users.id` (cascade delete)
- `name` (string)
- `quantity` (decimal 8,2, default: 1)
- `unit` (string, nullable) - measurement unit (g, ml, etc)
- `protein` (decimal 8,2, nullable) - in grams
- `fat` (decimal 8,2, nullable) - in grams
- `carbohydrate` (decimal 8,2, nullable) - in grams
- `fiber` (decimal 8,2, nullable) - in grams
- `calories` (decimal 8,2, nullable) - in kcal
- `barcode` (string, nullable)
- `is_favorite` (boolean, default: false)
- `source` (enum: 'manual', 'whatsapp', default: 'manual')
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### meal_items
Junction table linking meals to foods with quantities.
- `id` (bigint, primary key)
- `meal_id` (foreignId) → **references** `meals.id` (cascade delete)
- `food_id` (foreignId) → **references** `foods.id` (cascade delete)
- `quantity` (float)
- `notes` (text, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### recipes
User-created recipes with ingredients and instructions.
- `id` (bigint, primary key)
- `user_id` (foreignId) → **references** `users.id` (cascade delete)
- `name` (string)
- `ingredients` (text, nullable)
- `instructions` (text, nullable)
- `preparation_time` (integer, nullable) - in minutes
- `cooking_time` (integer, nullable) - in minutes
- `servings` (integer, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

### 3. User Profile & Settings

#### user_profiles
Stores user physical characteristics and metabolic data.
- `id` (bigint, primary key)
- `user_id` (foreignId) → **references** `users.id` (cascade delete)
- `weight` (decimal 5,1, nullable) - in kg
- `height` (integer, nullable) - in cm
- `gender` (string, nullable)
- `age` (integer, nullable)
- `activity_level` (string, nullable)
- `basal_metabolic_rate` (integer, nullable)
- `use_basal_metabolic_rate` (boolean, default: true)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### user_preferences
Comprehensive user preferences and feature toggles.
- `id` (bigint, primary key)
- `user_id` (foreignId) → **references** `users.id` (cascade delete)
- **Diet Evaluation Features:**
  - `glycemic_index_enabled` (boolean, default: false)
  - `cholesterol_enabled` (boolean, default: false)
  - `keto_diet_enabled` (boolean, default: false)
  - `paleo_diet_enabled` (boolean, default: false)
  - `low_fodmap_enabled` (boolean, default: false)
  - `low_carb_enabled` (boolean, default: false)
  - `meal_plan_evaluation_enabled` (boolean, default: false)
- **App Preferences:**
  - `time_zone` (string, default: 'UTC-3')
  - `silent_mode_enabled` (boolean, default: false)
  - `language` (string, default: 'Português')
  - `prioritize_taco_enabled` (boolean, default: false)
  - `daily_log_enabled` (boolean, default: true)
  - `photo_with_macros_enabled` (boolean, default: false)
  - `auto_fasting_enabled` (boolean, default: true)
  - `detailed_foods_enabled` (boolean, default: false)
  - `show_dashboard_enabled` (boolean, default: false)
  - `advanced_food_analysis_enabled` (boolean, default: false)
  - `group_water_enabled` (boolean, default: false)
- `expanded_sections` (json, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### nutritional_goals
User's nutritional targets.
- `id` (bigint, primary key)
- `user_id` (foreignId) → **references** `users.id` (cascade delete)
- `protein` (integer, nullable) - in grams
- `carbs` (integer, nullable) - in grams
- `fat` (integer, nullable) - in grams
- `fiber` (integer, nullable) - in grams
- `calories` (integer, nullable) - in kcal
- `water` (integer, nullable) - in ml
- `objective` (string, nullable) - weight loss, maintenance, or gain
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### user_plans
Stores diet and training plans.
- `id` (bigint, primary key)
- `user_id` (foreignId) → **references** `users.id` (cascade delete)
- `type` (enum: 'diet', 'training')
- `content` (text, nullable)
- `file_path` (string, nullable) - path to uploaded PDF
- `created_at` (timestamp)
- `updated_at` (timestamp)
- **Unique constraint**: (user_id, type)

### 4. Tracking & Measurement Tables

#### measurements
Tracks various body measurements over time.
- `id` (bigint, primary key)
- `user_id` (foreignId) → **references** `users.id` (cascade delete)
- `type` (enum: 'weight', 'body_fat', 'lean_mass', 'arm', 'forearm', 'waist', 'hip', 'thigh', 'calf', 'bmr', 'body_water')
- `value` (decimal 8,2)
- `notes` (text, nullable)
- `date` (date)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### diaries
Daily diary entries for tracking mood, water, and sleep.
- `id` (bigint, primary key)
- `user_id` (foreignId) → **references** `users.id` (cascade delete)
- `date` (date)
- `notes` (text, nullable)
- `mood` (integer, nullable) - 1-5 scale
- `water` (integer, default: 0) - in ml
- `sleep` (integer, nullable) - in minutes
- `created_at` (timestamp)
- `updated_at` (timestamp)
- **Unique constraint**: (user_id, date)

### 5. Reminder System

#### reminders
Configurable reminders for meals, water, etc.
- `id` (bigint, primary key)
- `user_id` (foreignId) → **references** `users.id` (cascade delete)
- `name` (string)
- `description` (text, nullable)
- `reminder_type` (string, default: 'intervalo de tempo')
- `interval_hours` (integer, default: 0)
- `interval_minutes` (integer, default: 0)
- `start_time` (time, nullable)
- `end_time` (time, nullable)
- `buttons_enabled` (boolean, default: false)
- `auto_command_enabled` (boolean, default: false)
- `auto_command` (string, nullable)
- `active` (boolean, default: true)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### reminder_details
Action buttons for reminders.
- `id` (bigint, primary key)
- `reminder_id` (foreignId) → **references** `reminders.id` (cascade delete)
- `button_text` (string)
- `button_action` (string, nullable)
- `display_order` (integer, default: 0)
- `created_at` (timestamp)
- `updated_at` (timestamp)

### 6. System Tables

#### cache
Laravel cache storage.
- `key` (string, primary key)
- `value` (mediumText)
- `expiration` (integer)

#### cache_locks
Cache lock management.
- `key` (string, primary key)
- `owner` (string)
- `expiration` (integer)

#### jobs
Queue job storage.
- `id` (bigint, primary key)
- `queue` (string, indexed)
- `payload` (longText)
- `attempts` (unsignedTinyInteger)
- `reserved_at` (unsignedInteger, nullable)
- `available_at` (unsignedInteger)
- `created_at` (unsignedInteger)

#### job_batches
Batch job management.
- `id` (string, primary key)
- `name` (string)
- `total_jobs` (integer)
- `pending_jobs` (integer)
- `failed_jobs` (integer)
- `failed_job_ids` (longText)
- `options` (mediumText, nullable)
- `cancelled_at` (integer, nullable)
- `created_at` (integer)
- `finished_at` (integer, nullable)

#### failed_jobs
Failed job tracking.
- `id` (bigint, primary key)
- `uuid` (string, unique)
- `connection` (text)
- `queue` (text)
- `payload` (longText)
- `exception` (longText)
- `failed_at` (timestamp, default: current)

#### suggestions
Food suggestions (currently unused).
- `id` (bigint, primary key)
- `name` (string)
- `description` (text, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

## Entity Relationships

### User Relationships (One-to-Many)
A user can have many:
- meals
- foods (custom foods)
- recipes
- measurements
- diary entries
- reminders
- one profile
- one preference set
- one nutritional goal
- diet and training plans

### Meal Relationships
- A meal belongs to one user
- A meal has many meal_items
- Each meal_item connects one meal to one food with a specific quantity

### Food Relationships
- A food can belong to a user (custom) or be public (user_id = null)
- A food can be used in many meal_items

### Reminder Relationships
- A reminder belongs to one user
- A reminder can have many reminder_details (action buttons)

## Key Constraints

1. **Cascade Deletes**: All foreign key relationships have cascade delete enabled
2. **Unique Constraints**:
   - users.email
   - personal_access_tokens.token
   - user_plans (user_id, type) combination
   - diaries (user_id, date) combination

## Indexes
- sessions.user_id
- sessions.last_activity
- jobs.queue
- personal_access_tokens (tokenable_type, tokenable_id)

## Enumerations

### meal_type
- cafe_da_manha (breakfast)
- almoco (lunch)
- lanche_da_tarde (afternoon snack)
- jantar (dinner)

### measurement_type
- weight
- body_fat
- lean_mass
- arm
- forearm
- waist
- hip
- thigh
- calf
- bmr
- body_water

### food_source
- manual
- whatsapp

### user_plan_type
- diet
- training