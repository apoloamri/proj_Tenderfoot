<?php
class Headers 
{
    static function Get(string $key) : string
    {
        $headers = apache_request_headers();
        if (array_key_exists($key, $headers))
        {
            return $headers[$key];
        }
        return null;
    }
}
?>