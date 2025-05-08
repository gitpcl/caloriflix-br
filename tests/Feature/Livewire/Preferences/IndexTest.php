<?php

use App\Livewire\Preferences\Index;
use App\Models\User;
use App\Models\UserPreference;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    // Create a user with preferences for testing
    $this->user = User::factory()->create();

    // Create default preferences for the user
    $this->preferences = UserPreference::factory()->create([
        'user_id' => $this->user->id,
        'glycemic_index_enabled' => false,
        'cholesterol_enabled' => true,
        'keto_diet_enabled' => false,
        'paleo_diet_enabled' => true,
        'low_fodmap_enabled' => false,
        'low_carb_enabled' => true,
        'meal_plan_evaluation_enabled' => false,
        'time_zone' => 'UTC-3',
        'silent_mode_enabled' => false,
        'language' => 'Português',
        'prioritize_taco_enabled' => true,
        'daily_log_enabled' => true,
        'photo_with_macros_enabled' => false,
        'auto_fasting_enabled' => true,
        'detailed_foods_enabled' => false,
        'show_dashboard_enabled' => true,
        'advanced_food_analysis_enabled' => false,
        'group_water_enabled' => true,
        'expanded_sections' => [
            'preferences' => true,
            'evaluations' => false,
            'personal_info' => true,
            'subscription_info' => false,
            'api_integration' => false,
        ],
    ]);
});

test('preferences component can be rendered', function () {
    actingAs($this->user);
    
    $component = Livewire::test(Index::class);
    
    $component->assertStatus(200);
});

test('preferences are loaded correctly on mount', function () {
    actingAs($this->user);
    
    $component = Livewire::test(Index::class);
    
    // Check that the component has loaded the user's preferences correctly
    $component->assertSet('glycemicIndexEnabled', false);
    $component->assertSet('cholesterolEnabled', true);
    $component->assertSet('ketoDietEnabled', false);
    $component->assertSet('paleoDietEnabled', true);
    $component->assertSet('lowFodmapEnabled', false);
    $component->assertSet('lowCarbEnabled', true);
    $component->assertSet('mealPlanEvaluationEnabled', false);
    $component->assertSet('timeZone', 'UTC-3');
    $component->assertSet('silentModeEnabled', false);
    $component->assertSet('language', 'Português');
    $component->assertSet('prioritizeTacoEnabled', true);
    $component->assertSet('dailyLogEnabled', true);
    $component->assertSet('photoWithMacrosEnabled', false);
    $component->assertSet('autoFastingEnabled', true);
    $component->assertSet('detailedFoodsEnabled', false);
    $component->assertSet('showDashboardEnabled', true);
    $component->assertSet('advancedFoodAnalysisEnabled', false);
    $component->assertSet('groupWaterEnabled', true);
    
    // Check expanded sections are loaded correctly
    $component->assertSet('expandedSections.preferences', true);
    $component->assertSet('expandedSections.evaluations', false);
    $component->assertSet('expandedSections.personal_info', true);
    $component->assertSet('expandedSections.subscription_info', false);
    $component->assertSet('expandedSections.api_integration', false);
});

test('toggle section updates expanded sections state', function () {
    actingAs($this->user);
    
    $component = Livewire::test(Index::class);
    
    // Initially 'evaluations' should be collapsed (false)
    $component->assertSet('expandedSections.evaluations', false);
    
    // Toggle the section
    $component->call('toggleSection', 'evaluations');
    
    // Now it should be expanded (true)
    $component->assertSet('expandedSections.evaluations', true);
    
    // Toggle it again
    $component->call('toggleSection', 'evaluations');
    
    // Now it should be collapsed again (false)
    $component->assertSet('expandedSections.evaluations', false);
    
    // Check that it was saved to the database
    $this->user->refresh();
    $updatedPreference = $this->user->preference->fresh();
    $expandedSections = $updatedPreference->expanded_sections;
    
    expect($expandedSections['evaluations'])->toBeFalse();
});

test('updating preference toggle saves to database', function () {
    actingAs($this->user);
    
    $component = Livewire::test(Index::class);
    
    // Initially keto diet should be disabled
    $component->assertSet('ketoDietEnabled', false);
    
    // Enable keto diet
    $component->set('ketoDietEnabled', true);
    
    // Refresh the user to get updated preferences from database
    $this->user->refresh();
    
    // Check that the preference was updated in the database
    expect($this->user->preference->fresh()->keto_diet_enabled)->toBeTrue();
});

test('updating preference value saves to database', function () {
    actingAs($this->user);
    
    $component = Livewire::test(Index::class);
    
    // Initially language should be 'Português'
    $component->assertSet('language', 'Português');
    
    // Update language to English
    $component->set('language', 'English');
    
    // The updated() hook should automatically trigger the save
    // Refresh the user to get updated preferences from database
    $this->user->refresh();
    
    // Check that the preference was updated in the database
    expect($this->user->preference->fresh()->language)->toBe('English');
});

test('guest users cannot access preferences', function () {
    // Test without authentication
    Livewire::test(Index::class)
        ->assertStatus(200); // Component still renders
        
    // Try to save a preference by changing a value
    // This should not create a preference in the database since user is not authenticated
    Livewire::test(Index::class)
        ->set('language', 'English');
        
    // Check that no preferences were created or updated
    expect(UserPreference::count())->toBe(1); // Only our test user's preferences
});

test('new users get default preferences', function () {
    // Create a new user without preferences
    $newUser = User::factory()->create();
    
    actingAs($newUser);
    
    // Load the component which should create default preferences in mount()
    Livewire::test(Index::class);
    
    // Refresh the user
    $newUser->refresh();
    
    // Check that preferences were created
    expect($newUser->preference)->not->toBeNull();
    
    // Default values should be set based on the component's initial values
    $preferences = $newUser->preference;
    expect($preferences->daily_log_enabled)->toBeTrue();
    expect($preferences->auto_fasting_enabled)->toBeTrue();
});

test('multiple preference changes are saved correctly', function () {
    actingAs($this->user);
    
    $component = Livewire::test(Index::class);
    
    // Make multiple changes
    $component->set('glycemicIndexEnabled', true);
    $component->set('lowCarbEnabled', false);
    $component->set('timeZone', 'UTC-5');
    
    // Refresh the user to get updated preferences from database
    $this->user->refresh();
    $preferences = $this->user->preference->fresh();
    
    // Check that all changes were saved correctly
    expect($preferences->glycemic_index_enabled)->toBeTrue();
    expect($preferences->low_carb_enabled)->toBeFalse();
    expect($preferences->time_zone)->toBe('UTC-5');
});

test('expanded sections are saved independently', function () {
    actingAs($this->user);
    
    $component = Livewire::test(Index::class);
    
    // Toggle multiple sections
    $component->call('toggleSection', 'preferences'); // Should become false
    $component->call('toggleSection', 'evaluations'); // Should become true
    
    // Check component state
    $component->assertSet('expandedSections.preferences', false);
    $component->assertSet('expandedSections.evaluations', true);
    
    // Refresh user and check database state
    $this->user->refresh();
    $expandedSections = $this->user->preference->fresh()->expanded_sections;
    
    expect($expandedSections['preferences'])->toBeFalse();
    expect($expandedSections['evaluations'])->toBeTrue();
});

test('component handles empty expanded sections gracefully', function () {
    // Update user's preferences to have null expanded_sections
    $this->preferences->update(['expanded_sections' => null]);
    
    actingAs($this->user);
    
    // Component should load without errors and use default values
    $component = Livewire::test(Index::class);
    
    // Should have default expanded sections from the component
    $component->assertSet('expandedSections.preferences', false);
    $component->assertSet('expandedSections.evaluations', false);
});

test('component handles database updates from other sources', function () {
    actingAs($this->user);
    
    // Make a change directly to the database (simulating another session/browser)
    $this->preferences->update([
        'low_carb_enabled' => false,
        'time_zone' => 'UTC-4',
    ]);
    
    // Create a fresh component (simulating a page reload)
    $component = Livewire::test(Index::class);
    
    // Component should load the updated values from database
    $component->assertSet('lowCarbEnabled', false);
    $component->assertSet('timeZone', 'UTC-4');
});
