<?php

namespace Telenok\Core\Support;

class LocalizationPlaceholder {

    protected static $value = [];

    public static function get()
    {
        static::core();

        return static::$value;
    }

    public static function core()
    {
        static $once = false;
        
        if (!$once)
        {
            $once = true;
            
            static::$value = [
                'locale' => \Config::get('app.locale')
            ];
        }
    }

    public static function extend($value = [])
    {
        static::$value = array_merge(static::$value, $value);
    }
    
}


?>
