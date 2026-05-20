<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Watson\Rememberable\Rememberable;
use Auth, Setting;

class BaseModel extends Model
{

    use Rememberable;


    /**
     * Validator rules
     *
     * @var array
     */
    protected $rules = array();

    /**
     * Validator friendly attributes
     *
     * @var array
     */
    protected $customAttributes = array();

    /**
     * Validator custom messages
     *
     * @var array
     */
    protected $customMessages = array();

    /**
     * Validator errors
     *
     * @var array
     */
    protected $errors;

    /**
     * Validate rules for this model
     *
     * @param array $data
     * @param array $rules
     * @return boolean
     */
    public function validate($data, $rules = null)
    {
        if ($rules) {
            $rules = array_intersect_key($rules, $data);
        } else {
            $rules = array_intersect_key($this->rules, $data);
        }

        // Make a new validator object
        $validator = Validator::make($data, $rules, $this->customMessages, $this->customAttributes);

        // Check for failure
        if ($validator->fails()) {
            // Set errors and return false
            $this->errors = $validator->errors();
            return false;
        }

        // Validation pass, return true
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
     * Join relation to query and remember
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $relation
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeWithRemember($query, $relation)
    {
        return $query->with([$relation => function ($q) {
            $q->remember(Setting::get('cache_duration'));
        }]);
    }

    /**
     * Join translations to query and remember it
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $relation
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeWithRememberTranslation($query)
    {
        return $query->with(['translations' => function ($q) {
            $q->remember(Setting::get('cache_duration'));
        }]);
    }

    /**
     * Join translations to query and remember it
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $relation
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeRememberQuery($query, $duration = null)
    {
        return $query->remember($duration ? $duration : Setting::get('cache_duration'));
    }

    public function scopeFilterSource($query)
    {
        return $query->where('source', config('app.source'));
    }
}
