<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\CustomerResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Rememberable\Rememberable;
use App\Models\Traits\FileTrait;
use Validator, Setting, Hash;

class Customer extends Authenticatable
{
    use Notifiable,
        SoftDeletes,
        Rememberable,
        FileTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';


    /**
     * Default upload directory
     *
     * @const string
     */
    const DIRECTORY = 'uploads/customers';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_login', 'balance_last_update'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Validator rules
     *
     * @var array
     */
    public $rules = [
        'name'      => 'required',
        'email'     => 'required',
        'address'   => 'required',
        'phone'     => 'required'
    ];

    /**
     * Validator custom attributes
     *
     * @var array
     */
    protected $customAttributes = [
        'name'      => 'Nome',
        'email'     => 'E-mail',
        'address'   => 'Morada',
        'phone'     => 'Telefone'
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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }


    /**
     * Filter is active
     *
     * @return self
     */
    public function scopeIsActive($query, $isActive = true)
    {
        return $this->where('is_active', $isActive);
    }

    /**
     * Filter is prospect
     *
     * @return self
     */
    public function scopeIsProspect($query, $isProspect = true)
    {
        return $this->where('is_prospect', $isProspect);
    }




    /**
     * Create tracking code
     *
     * @return string
     */
    public function setBankMandateCode($save = false)
    {
        $maxMandate = Customer::withTrashed()->where('bank_mandate', 'like',  date('y') . '%')->max('bank_mandate');
        $maxMandate = (int) substr($maxMandate, 4);
        $maxMandate++;

        $code = date('ym') . str_pad($maxMandate, 4, "0", STR_PAD_LEFT);

        if ($save) {
            $this->bank_mandate = $code;
            $this->save();
        }

        return $code;
    }

    /**
     * Return next model ID
     * @param $query
     * @return mixed
     */
    public function nextId()
    {
        return Customer::where('id', '>', $this->id)
            ->min('id');
    }


    /**
     * Return previous model ID
     * @param $query
     * @return mixed
     */
    public function previousId()
    {
        return Customer::where('id', '<', $this->id)
            ->max('id');
    }

    /*
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     |
     | Define current model relationships
     */
    public function scopeFilterSource($query)
    {
        return $query->where('source', config('app.source'));
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | Define current model relationships
    */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
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
    public function setCategoryIdAttribute($value)
    {
        $this->attributes['category_id'] = empty($value) ? null : $value;
    }

    public function setManagerIdAttribute($value)
    {
        $this->attributes['manager_id'] = empty($value) ? null : $value;
    }

    public function setBankIbanAttribute($value)
    {
        $this->attributes['bank_iban'] = strtoupper(str_replace(' ', '', $value));
    }

    public function setSupportMinsAttribute($value)
    {
        $this->attributes['support_mins'] = empty($value) ? null : $value;
    }

    public function setSupportPriceAttribute($value)
    {
        $this->attributes['support_price'] = empty($value) ? null : $value;
    }

    public function setBillingNameAttribute($value)
    {
        $this->attributes['billing_name'] = empty($value) ? $this->attributes['name'] : $value;
    }

    public function setBillingAddressAttribute($value)
    {
        $this->attributes['billing_address'] = empty($value) ? $this->attributes['address'] : $value;
    }

    public function setBillingZipCodeAttribute($value)
    {
        $this->attributes['billing_zip_code'] = empty($value) ? $this->attributes['zip_code'] : $value;
    }

    public function setBillingCityAttribute($value)
    {
        $this->attributes['billing_city'] = empty($value) ? $this->attributes['city'] : $value;
    }

    public function setBillingCountryAttribute($value)
    {
        $this->attributes['billing_country'] = empty($value) ? $this->attributes['country'] : $value;
    }

    public function setBillingEmailAttribute($value)
    {
        $this->attributes['billing_email'] = empty($value) ? @$this->attributes['contact_email'] : $value;
    }

    public function setContractDateAttribute($value)
    {
        $this->attributes['contract_date'] = empty($value) ? null : $value;
    }

    public function setIpAttribute($value)
    {
        $this->attributes['ip'] = ip2long($value);
    }

    public function getSupportMinsAttribute()
    {
        return $this->attributes['support_mins'] ? $this->attributes['support_mins'] : Setting::get('support_minutes');
    }

    public function getIpAttribute()
    {
        return long2ip($this->attributes['ip']);
    }

    public function getCodeNameAttribute()
    {
        return sprintf('%s - %s', $this->attributes['code'], $this->attributes['name']);
    }
}
