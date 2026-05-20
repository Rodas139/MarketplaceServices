<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

class Comission extends Model
{
    use SoftDeletes;

    protected $table = 'comissions';

    protected $fillable = [
        'id_payment',
        'value',
        'gain'
    ];

    /**
     * Validator rules
     *
     * @var array
     */
    public $rules = [
        'id_payment' => 'required|exists:payments,id',
        'value' => 'required|numeric|min:0',
        'gain' => 'nullable|numeric|min:0'
    ];

    /**
     * Validator custom attributes
     *
     * @var array
     */
    protected $customAttributes = [
        'id_payment' => 'Pagamento',
        'value' => 'Valor',
        'gain' => 'Ganho'
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
     * Get the payment associated with the comission.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id_payment');
    }
}
