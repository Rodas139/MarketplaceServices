<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vehicles_tables';

    protected $fillable = [
        'code_user',
        'user_id',
        'nome',
        'matricula',
    ];

    /**
     * Get the user associated with the vehicle.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
