<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'id_order',
        'method',
        'total',
        'status'
    ];

    /**
     * Validator rules
     *
     * @var array
     */
    public $rules = [
        'id_order' => 'required|exists:orders,id',
        'method' => 'nullable|string',
        'total' => 'required|numeric|min:0',
        'status' => 'nullable|in:pending,paid'
    ];

    /**
     * Validator custom attributes
     *
     * @var array
     */
    protected $customAttributes = [
        'id_order' => 'Pedido',
        'method' => 'Método',
        'total' => 'Total',
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
     * Get the order associated with the payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
