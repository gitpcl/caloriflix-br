<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Measurement extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'value',
        'notes',
        'date',
    ];
    
    /**
     * The measurement types available in the system
     * 
     * @var array<string, string>
     */
    public static $types = [
        'weight' => 'Peso',
        'body_fat' => 'Gordura Corporal',
        'lean_mass' => 'Massa Magra',
        'arm' => 'Braço',
        'forearm' => 'Antebraço',
        'waist' => 'Cintura',
        'hip' => 'Quadril',
        'thigh' => 'Coxa',
        'calf' => 'Panturrilha',
        'bmr' => 'Taxa Metabólica Basal',
        'body_water' => 'Água Corporal'
    ];
    
    /**
     * The measurement units for each type
     * 
     * @var array<string, string>
     */
    public static $units = [
        'weight' => 'kg',
        'body_fat' => '%',
        'lean_mass' => 'kg',
        'arm' => 'cm',
        'forearm' => 'cm',
        'waist' => 'cm',
        'hip' => 'cm',
        'thigh' => 'cm',
        'calf' => 'cm',
        'bmr' => 'kcal',
        'body_water' => '%'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];
    
    /**
     * Get the user that owns the measurement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
