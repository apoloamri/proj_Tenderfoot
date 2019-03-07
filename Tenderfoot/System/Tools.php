<?php
class Chars
{
    static function Between(string $string, string $start, string $end) : string
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    static function Contains(string $needle, string $haystack) : bool
    {
        return strpos($haystack, $needle) !== false;
    }
    static function StartsWith(string $needle, string $haystack) : bool
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    static function EndsWith(string $needle, string $haystack) : bool
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }
    static function Clip(string $subject, int $count) : string
    {
        if (strlen($subject) > $count)
        {
            return substr($subject, 0, $count);
        }
        return $subject;
    }
    static function Random(int $length = 10) : string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) 
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
class Date
{
    static function Now(int $minutes = 0) : string
    {
        date_default_timezone_set(Settings::TimeZone());
        $minutes = $minutes * 60;
        return date("Y/m/d H:i:s", time() + $minutes);
    }
}
class Obj
{
    static function HasValue($value) : bool
    {
        if (!isset($value))
        {
            return false;
        }
        else if (is_object($value))
        {
            return true;
        }
        else if (is_array($value))
        {
            return count($value) > 0;
        }
        else if (is_bool($value))
        {
            return true;
        }
        else if (strlen($value) == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    static function Overwrite($model, $values)
    {
        $modelReflect = new ReflectionClass($model);
        foreach ($modelReflect->getProperties(ReflectionProperty::IS_PUBLIC) as $property)
        {
            $name = $property->getName();
            if (array_key_exists($name, $values))
            {
                $property->setValue($model, $values->$name);
            }
        }
        return $model;
    }
}
class TempData
{
    public static function Get($key) 
    {
        if (!array_key_exists($key, $_SESSION))
        {
            return null;
        }
        else
        {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }
    }
    public static function Set($key, $value) : void
    {
        $_SESSION[$key] = $value;
    }
}
?>