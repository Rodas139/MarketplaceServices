<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Notification extends BaseModel
{

    use SoftDeletes;

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_notifications';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'title',
        'message',
        'type',
        'source_class',
        'source_id',
        'params',
        'read_by',
        'read_by_admin',
        'read_by_customer',
        'alert_at'
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'alert_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Casts
     */
    protected $casts = [
        'params' => 'json',
        'read_by' => 'array',
        'read_by_admin' => 'array',
        'read_by_customer' => 'array'
    ];

    /**
     * Validator rules
     *
     * @var array
     */
    protected $rules = array(
        'title'   => 'required',
        'message' => 'required',
    );

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeRecent($query)
    {
        return $query->orderBy('alert_at', 'desc');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public static function scopeUnread($query){
        return $query->where('read', 0)
             ->where(function($q){
                 $q->whereNull('alert_at')
                   ->orWhereRaw('alert_at <= NOW()');
             });
    }

    public static function scopeScheduledToday($query){
        return $query->where('recipient', Auth::user()->id)
                     ->whereNotNull('alert_at')
                     ->whereRaw('DATE(alert_at) = TODAY()');
    }

    public function setSenderAttribute($value)
    {
        $this->attributes['sender'] = empty($value) ? null : $value;
    }
}
