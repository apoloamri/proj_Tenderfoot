<?php
class Headers 
{
    static function Get(string $key) : string
    {
        $headers = apache_request_headers();
        return $headers[$key];
    }
}
?>