<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeLog extends BaseModel
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'change_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source', 'source_id', 'action', 'old', 'new', 'user_id'
    ];

    /**
     * Validator rules
     *
     * @var array
     */
    protected $rules = array(
        'source'    => 'required',
        'source_id' => 'required',
        'action'    => 'required',
        'old'       => 'required',
        'new'       => 'required',
        'user_id'   => 'required',
    );

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     | Define current model relationships
     */
    public function source()
    {
        return $this->belongsTo($this->source, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    | Eloquent provides a convenient way to transform your model attributes when
    | getting or setting them. Simply define a 'getFooAttribute' method on your model
    | to declare an accessor. Keep in mind that the methods should follow camel-casing,
    | even though your database columns are snake-case.
    |
    */
    public function setOldAttribute($value)
    {
        $this->attributes['old'] = empty($value) ? null : json_encode($value);
    }

    public function setNewAttribute($value)
    {
        $this->attributes['new'] = empty($value) ? null : json_encode($value);
    }

    public function getOldAttribute($value)
    {
        return json_decode($value);
    }

    public function getNewAttribute($value)
    {
        return json_decode($value);
    }
}
