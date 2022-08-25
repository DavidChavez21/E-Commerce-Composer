<?php

namespace Utilities;

abstract class Enum
{
    const NONE = null;
    private function __construct()
    {
        throw new NotSupportedException(); // 
    }
    private function __clone()
    {
        throw new NotSupportedException();
    }
    public static function toArray()
    {
        return (new ReflectionClass(static::class))->getConstants();
    }
    public static function toFormatedArray()
    {
        $unFormated = (new ReflectionClass(static::class))->getConstants();
        $formated = array();
        foreach($unFormated as $key => $value) {
            $formated[] = array("code" => $key, "value" => $value);
        }
        return $formated;
    }
    
    public static function isValid($value)
    {
        return in_array($value, static::toArray());
    }
}

?>
