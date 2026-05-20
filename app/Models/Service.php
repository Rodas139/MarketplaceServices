<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

class Service extends Model
{
    use SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'status'
    ];

    /**
     * Validator rules
     *
     * @var array
     */
    public $rules = [
        'name'      => 'required',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categorys,id',
        'status' => 'nullable|in:pending,active,inactive'
    ];

    /**
     * Validator custom attributes
     *
     * @var array
     */
    protected $customAttributes = [
        'name'      => 'Nome',
        'price' => 'Preço',
        'category_id' => 'Categoria',
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
     * Get the category associated with the service.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the users (providers) who offer this service.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_service');
    }

    /**
     * Get the orders associated with this service.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'service_id');
    }
}
