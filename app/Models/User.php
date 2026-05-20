<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Rememberable\Rememberable;
use App\Models\Traits\FileTrait;
use Auth, Date, DB, Setting, Pdf;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, Rememberable, FileTrait, SoftDeletes, HasRoles;

    //  EntrustUserTrait {
    //     SoftDeletes::restore insteadof EntrustUserTrait;
    //     EntrustUserTrait::restore insteadof SoftDeletes;
    // }

    /**
     * Constant variables
     */
    const CACHE_TAG = 'cache_users';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'last_login'];

    /**
     * Default upload directory
     *
     * @const string
     */
    const DIRECTORY = 'uploads/users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'id_service'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
     * Return HTML signature for current user
     * @return array|string|string[]
     */
    public function getMailSignature($html = true)
    {

        if ($html) {
            $signature = Setting::get('tickets_mail_signature');
            $signature = str_replace('_NAME_', $this->name, $signature);
            $signature = str_replace('_EMAIL_', ($this->profissional_email ? $this->profissional_email : $this->email), $signature);
            $signature = str_replace('_PHONE_', $this->profissional_phone, $signature);
        } else {
            $signature = "\n\n" . $this->name;
            $signature -= $this->email;
        }

        return $signature;
    }

    /**
     * Get user setting
     * @param $var
     * @param mixed $default
     * @return null
     */
    public function setting($var, $default = null)
    {
        $settings = $this->settings;
        return @$settings[$var] ? $settings[$var] : $default;
    }


    /**
     * Store user settings
     * @param $var
     * @param $value
     * @param bool $save
     * @return mixed
     */
    public function setSetting($var, $value, $save = true)
    {
        $settings = $this->settings;

        $this->settings = $settings[$var] = $value;

        if ($save) {
            $this->save();
        }

        return $settings;
    }


    /**
     * Store user settings from array
     * @param $var
     * @param $value
     * @param bool $save
     * @return mixed
     */
    public function setSettingArray($settingsArr, $save = true)
    {
        $settings = $this->settings;

        $hasChanges = false;
        foreach ($settingsArr as $key => $value) {
            if (@$settings[$key] != $value) {
                $hasChanges = true;
            }

            $settings[$key] = $value;
        }

        $this->settings = $settings;

        if ($save && $hasChanges) {
            $this->save();
        }

        return $settings;
    }

    /**
     * Check if user has agency
     *
     * @param int|array $agency
     * @return boolean
     */
    public function hasAgency($agency)
    {
        $agencies = $this->agencies;

        if ($this->hasRole([config('permission.role.admin')])) {
            return true;
        }

        if ($agencies) {

            if (is_array($agency)) {
                return empty(array_intersect($agency, $agencies)) ? false : true;
            } elseif (in_array($agency, $agencies)) {
                return true;
            }
        }

        return false;
    }


    /**
     * Check if user is allowed to take an action
     *
     * @param $permission
     * @return bool
     */
    public function allowedAction($permission)
    {
        if ($this->hasRole([config('permission.role.admin')])) {
            return true;
        }

        $actions = (array) $this->allowed_actions;

        if (isset($actions[$permission]) && $actions[$permission] == '1') {
            return true;
        }

        return false;
    }

    /**
     * Create map marker from uploaded image
     *
     * @param $permission
     * @return bool
     */
    public function createMapMarker()
    {
        $filepath = $this->filepath;
        $path     = '/uploads/users/marker_' . $this->id . '.png';
        $destination = public_path() . $path;

        cropImageRounded($filepath, $destination);

        $this->location_marker = $path;
        $this->save();

        return true;
    }

    /**
     * Return next model ID
     * @param $query
     * @return mixed
     */
    public function nextId()
    {
        return User::where('id', '>', $this->id)
            ->min('id');
    }

    /**
     * Return previous model ID
     * @param $query
     * @return mixed
     */
    public function previousId()
    {
        return User::where('id', '<', $this->id)
            ->max('id');
    }

    /**
     * Filter is active
     *
     * @param $query
     * @param bool $isActive
     * @return mixed
     */
    public function scopeIsActive($query, $isActive = true)
    {
        return $query->where('active', $isActive);
    }

    /**
     * Ignore users that are admin
     *
     * @param $query
     * @return mixed
     */
    public function scopeIgnoreAdmins($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', '<>', config('permission.role.admin'));
        });
    }

    public function getLocationStatus()
    {

        $location = $this->last_location;

        if (empty($location->latitude) || !$this->location_enabled) {
            return 'disabled';
        } else {

            $now = Date::now();
            $lastStatus = new Date($location->created_at);

            if ($lastStatus->diffInSeconds($now) >= 7200) { //2h
                return 'disabled';
            } elseif ($lastStatus->diffInSeconds($now) >= 3600) { //1h
                return 'timeago';
            } else {
                return 'enabled';
            }
        }
    }

    public function getLocationMarker()
    {

        $location = $this->last_location;

        if (empty($location->latitude) || !$this->location_enabled) {
            return null;
        } else {

            $now = Date::now();
            $lastStatus = new Date($location->created_at);

            if ($lastStatus->diffInSeconds($now) >= 7200) { //2h
                $marker = asset('assets/img/default/marker_offline.png');
            } elseif ($lastStatus->diffInSeconds($now) >= 3600) { //1h
                $marker = asset('assets/img/default/marker_timeago.png');
            } else {
                $marker = asset('assets/img/default/marker.png');
            }
        }

        return $this->filehost . ($this->filepath ? $this->location_marker : $marker);
    }

    /**
     * Store settings array to customer
     *
     * @param $permissions
     * @return mixed
     */
    public function storeSettings($settingsArr, $autosave = true)
    {
        $arr = $this->settings;
        $arr = empty($arr) ? [] : $arr;
        $newArr = array_merge($arr, $settingsArr);
        $this->settings = $newArr;
        return $this->save();
    }

    /**
     * Store settings array to customer
     *
     * @param $permissions
     * @return mixed
     */
    public function deleteSettings($settingKey, $autosave = true)
    {
        $arr = $this->settings;

        if (empty(@$arr[$settingKey])) {
            return false;
        }

        if (empty($arr)) {
            return true;
        }

        unset($arr[$settingKey]);

        $this->settings = $arr;
        return $this->save();
    }

    /**
     * Check if user have some permissions
     * @param $permissions
     * @return mixed
     */
    public function getSetting($settingKey)
    {
        $arr = json_decode($this->attributes['settings'], true);
        return @$arr[$settingKey];
    }


    /**
     * Check if user have some permissions
     * @param $permissions
     * @return mixed
     */
    public function perm($permissions)
    {
        return $this->hasPermissionTo($permissions);
    }

    /**
     * Check if user have some permissions
     * @param $permissions
     * @return mixed
     */
    public function hasPermissionTo($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = (array) $permissions;
        }

        return Auth::user()->getPermissionsViaRoles()->pluck('name')->contains(function ($perm) use ($permissions) {
            return in_array($perm, $permissions);
        });
    }

    /**
     * Check if user is admin
     *
     * @return mixed
     */
    public function isAdmin($isAdmin = true)
    {
        if ($isAdmin) {
            return Auth::user()->hasRole(config('permission.role.admin'));
        } else {
            return Auth::user()->whereHas('role', function ($q) {
                return $q->where('name', '<>', config('permission.role.admin'));
            });
        }
    }

    /**
     * Check if user is guest agency
     *
     * @return mixed
     */
    public function isGuest()
    {
        return Auth::user()->hasRole(config('permission.role.guest_agency'));
    }

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

    public function chief()
    {
        return $this->belongsTo(User::class, 'professional_chief_id');
    }

    /**
     * Get the service provided by this user
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = empty($value) ? null : $value;
    }

    public function setIpAttribute($value)
    {
        $this->attributes['ip'] = ip2long($value);
    }

    public function setAllowedActionsAttribute($value)
    {
        $this->attributes['allowed_actions'] = empty($value) ? null : json_encode($value);
    }

    public function setSettingsAttribute($value)
    {
        $this->attributes['settings'] = empty($value) ? null : json_encode($value);
    }

    public function setSourceAttribute($value)
    {
        $this->attributes['source'] = empty($value) ? null : $value;
    }

    public function setProviderIdAttribute($value)
    {
        $this->attributes['provider_id'] = empty($value) ? null : $value;
    }

    public function setSalaryExpensesAttribute($value)
    {
        $this->attributes['salary_expenses'] = empty($value) ? null : $value;
    }

    public function setSalaryPriceAttribute($value)
    {
        $this->attributes['salary_price'] = empty($value) ? null : $value;
    }

    public function setSalaryFoodAllowanceAttribute($value)
    {
        $this->attributes['salary_food_allowance'] = empty($value) ? null : $value;
    }

    public function setSalaryValueHourAttribute($value)
    {
        $this->attributes['salary_value_hour'] = empty($value) ? null : $value;
    }

    public function setSalaryWorkingTimeExemptionAttribute($value)
    {
        $this->attributes['salary_working_time_exemption'] = empty($value) ? null : $value;
    }

    public function setProfessionalChiefAttribute($value)
    {
        $this->attributes['professional_chief'] = empty($value) ? null : $value;
    }

    public function setBirthdateAttribute($value)
    {
        $this->attributes['birthdate'] = empty($value) ? null : $value;
    }

    public function setAdmissionDateAttribute($value)
    {
        $this->attributes['admission_date'] = empty($value) ? null : $value;
    }

    public function getAllowedActionsAttribute()
    {
        return json_decode(@$this->attributes['allowed_actions'], true);
    }

    public function getSettingsAttribute()
    {
        return json_decode(@$this->attributes['settings'], true);
    }

    public function setLocationLatAttribute($value)
    {
        $this->attributes['location_lat'] = empty($value) || $value == 0.00 ? null : $value;
    }

    public function setLocationLngAttribute($value)
    {
        $this->attributes['location_lng'] = empty($value) || $value == 0.00 ? null : $value;
    }

    public function getLocationMarkerAttribute()
    {
        return empty($this->attributes['location_marker']) ? asset('assets/img/default/marker.png') : $this->attributes['location_marker'];
    }

    public function getShowRefenceAttribute()
    {
        $showReference = @$this->attributes['show_reference'];

        if (is_null($showReference)) {
            $showReference = Setting::get('show_customers_reference');
        }

        return $showReference;
    }
}
