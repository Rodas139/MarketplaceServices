<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_customer',
        'id_order',
        'comment',
        'image',
        'rating'
    ];

    /**
     * The validation rules for the model.
     *
     * @var array
     */
    public $rules = [
        'id_customer' => 'required|exists:customers,id',
        'id_order' => 'required|exists:orders,id',
        'comment' => 'required|string',
        'image' => 'nullable|max:2048',
        'rating' => 'nullable|integer|min:1|max:5'
    ];

    /**
     * The validation attributes.
     *
     * @var array
     */
    public $attributes_labels = [
        'id_customer' => 'Cliente',
        'id_order' => 'Pedido',
        'comment' => 'Comentário',
        'image' => 'Imagem',
        'rating' => 'Avaliação'
    ];

    /**
     * Get the customer that owns the comment.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    /**
     * Get the order that owns the comment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
