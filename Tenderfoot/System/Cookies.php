<?php
session_start();
$expiration = Settings::Session() * 1000;
foreach ($_SESSION as $key => $value)
{
    if (Chars::Contains("cookie.", $key))
    {
        $newKey = str_replace("cookie.", "", $key);
        setcookie($newKey, $value->value, $value->expiration, $value->path);
    }
}
class Cookie
{
    static function New(string $key, $value, string $path = "/") : void
    {
        $cookie = new stdClass();
        $cookie->key = $key;
        $cookie->value = $value;
        $cookie->expiration = 0;
        $cookie->path = $path;
        $_SESSION["cookie.".$key] = $cookie;
    }
    static function Get(string $key) : string
    {
        if (array_key_exists("cookie.".$key, $_SESSION))
        {
            $cookie = $_SESSION["cookie.".$key];
            return 
                is_null($cookie->value) ?
                "" :
                $cookie->value;
        }
        else if (array_key_exists($key, $_COOKIE))
        {
            unset($_SESSION["cookie.$key"]);
            return $_COOKIE[$key];
        }
        return "";
    }
    static function Delete(string $key, string $path) : void
    {
        $cookie = new stdClass();
        $cookie->key = $key;
        $cookie->value = "";
        $cookie->expiration = -1;
        $cookie->path = $path;
        $_SESSION["cookie.".$key] = $cookie;
    }
}
class Session
{
    static function Set($value = null, string $environment = null) : string
    {
        $sessions = new Sessions();
        $sessions->session_key = $value;
        $sessionId = $sessions->Set();
        Cookie::New("$environment.SessionId", $sessionId, "/$environment");
        Cookie::New("$environment.SessionKey", $value, "/$environment");
        return $sessionId;
    }
    static function Check(string $environment = null) : bool
    {
        if (Cookie::Get("$environment.SessionId") != null)
        {
            $sessionValue = Session::Get($environment);
            $sessions = new Sessions();
            $sessions->session_id = $sessionValue->SessionId;
            if (Obj::HasValue($sessionValue->SessionKey))
            {
                $sessions->session_key = $sessionValue->SessionKey;
            }
            return $sessions->Check();
        }
        return false;
    }
    static function Get(string $environment = null) : object
    {
        $returnValue = new stdClass();;
        $returnValue->SessionId = Cookie::Get("$environment.SessionId");
        $returnValue->SessionKey = Cookie::Get("$environment.SessionKey");
        return $returnValue;
    }
    static function Delete(string $environment = null) : void
    {
        Cookie::Delete("$environment.SessionId", "/$environment");
        Cookie::Delete("$environment.SessionKey", "/$environment");
    }
}
?>