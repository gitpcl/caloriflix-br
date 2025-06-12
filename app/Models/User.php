<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserPreference;
use App\Models\UserProfile;
use App\Models\NutritionalGoal;
use App\Models\UserPlan;
use App\Models\Measurement;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }
    
    /**
     * Get the preferences associated with the user.
     */
    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }
    
    /**
     * Get the profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }
    
    /**
     * Get the nutritional goal associated with the user.
     */
    public function nutritionalGoal(): HasOne
    {
        return $this->hasOne(NutritionalGoal::class);
    }
    
    /**
     * Get all the plans associated with the user.
     */
    public function plans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }
    
    /**
     * Get the diet plan associated with the user.
     */
    public function dietPlan()
    {
        return $this->plans()->diet()->first();
    }
    
    /**
     * Get the training plan associated with the user.
     */
    public function trainingPlan()
    {
        return $this->plans()->training()->first();
    }
    
    /**
     * Get the measurements associated with the user.
     */
    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }
}
