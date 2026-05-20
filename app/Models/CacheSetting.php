<?php

namespace App\Models;

use File;

class CacheSetting
{

    public $filename = 'cache_settings.json';

    /**
     * Get variable
     * @param string $settingName
     * @return mixed
     */
    public static function get($settingName = null)
    {
        if($settingName) {
            $dataArr = self::getDataArr();
            return @$dataArr[$settingName];
        }

        return self::getDataArr();
    }

    /**
     * Store or update variable
     *
     * @param string $settingName
     * @return array
     */
    public static function set($varName, $varValue)
    {
        $data = Self::getDataArr();
        $data[$varName] = $varValue;
        return self::storeDataArr($data);
    }

    /**
     * Delete setting variable
     *
     * @param $varName
     * @return mixed
     */
    public static function delete($varName)
    {
        $arr = self::get();
        unset($arr[$varName]);
        return self::storeDataArr($arr);
    }

    /**
     * Get data array
     * @param $dataArr
     * @return mixed
     */
    public static function getDataArr()
    {
        $class = new CacheSetting();
        $filename = $class->filepath();

        try {
            $data = File::get($filename);
            $data = json_decode($data, true);
        } catch (\Exception $e) {
            $data = [];
        }

        return $data;
    }

    /**
     * Store array data
     * @param $dataArr
     * @return mixed
     */
    public static function storeDataArr($dataArr)
    {
        $class = new CacheSetting();
        $filename = $class->filepath();

        return File::put($filename, json_encode($dataArr));
    }

    /**
     * Destroy all cache file
     *
     * @return mixed
     */
    public static function reset()
    {
        $class = new CacheSetting();
        $filename = $class->filepath();
        $result = File::delete($filename);
        return $result;
    }

    /**
     * Return filename
     * @return string
     */
    public function filepath() {
        return storage_path() . '/' . $this->filename;
    }
}
