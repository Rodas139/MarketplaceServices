<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileRepository extends Model
{
    use SoftDeletes;

    protected $table = 'file_repositories';

    protected $fillable = [
        // ... (keep existing fillable fields as appropriate)
    ];

    /**
     * Relationships
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'source_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'source_id');
    }

    public function created_customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'source_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'source_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(FleetGest\Vehicle::class, 'source_id');
    }

    public function purchase_invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'source_id');
    }
}
