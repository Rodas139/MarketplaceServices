<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class LoginLog extends BaseModel
{

    use SoftDeletes;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_login_logs';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'login_logs';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source', 'target', 'user_id', 'success', 'ip', 'remember'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }
    
    public function scopeFilterSource($query) {
        return $query->where('source', env('APP_SOURCE'));
    }
}
