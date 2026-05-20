<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'id_customer',
        'user_id',
        'service_id',
        'date',
        'hour',
        'description',
        'status'
    ];

    /**
     * Validator rules
     *
     * @var array
     */
    public $rules = [
        'id_customer' => 'required|exists:customers,id',
        'user_id' => 'required|exists:users,id',
        'service_id' => 'nullable|exists:services,id',
        'date' => 'required|date',
        'hour' => 'nullable|integer|between:0,23',
        'description' => 'nullable|string',
        'status' => 'nullable|in:pending,accepted,declined,completed'
    ];

    /**
     * Validator custom attributes
     *
     * @var array
     */
    protected $customAttributes = [
        'id_customer' => 'Cliente',
        'user_id' => 'Utilizador',
        'service_id' => 'Serviço',
        'date' => 'Data',
        'hour' => 'Hora',
        'description' => 'Descrição',
        'status' => 'Status'
    ];

    /**
     * Validate rules for this model
     *
     * @param array $data
     * @return boolean
     */
    public function validate($data)
    {
        $rules = array_intersect_key($this->rules, $data);
        $validator = Validator::make($data, $rules, [], $this->customAttributes);
        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }
        return true;
    }

    /**
     * Get errors from validator
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Mutator to convert date format from dd/mm/yyyy to yyyy-mm-dd
     */
    public function setDateAttribute($value)
    {
        if ($value) {
            if (strpos($value, '/') !== false) {
                $parts = explode('/', $value);
                if (count($parts) === 3) {
                    $this->attributes['date'] = $parts[2] . '-' . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                    return;
                }
            }
            $this->attributes['date'] = $value;
        }
    }

    /**
     * Accessor to convert date format from yyyy-mm-dd to d/m/Y
     */
    public function getDateAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::createFromFormat('Y-m-d', $value);
        }
        return null;
    }

    /**
     * Get the customer associated with the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    /**
     * Get the service associated with the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the service associated with the order.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Get the comments associated with the order.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_order');
    }

    /**
     * Get the payments associated with the order.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_order');
    }

    /**
     * Get the total price for the order (hour * service price)
     */
    public function getTotalAttribute()
    {
        if ($this->hour && $this->user && $this->user->service) {
            return $this->hour * $this->user->service->price;
        }
        return 0;
    }
}
