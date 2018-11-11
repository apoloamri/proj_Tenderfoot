<?php
session_start();
$expiration = Settings::Session() * 1000;
foreach ($_SESSION as $key => $value)
{
    if (StringContains("cookie.", $key))
    {
        $newKey = str_replace("cookie.", "", $key);
        setcookie($newKey, $value->value, $value->expiration, $value->path);
    }
}
function NewCookie(string $key, $value, string $path) : void
{
    $cookie = new stdClass();
    $cookie->key = $key;
    $cookie->value = $value;
    $cookie->expiration = 0;
    $cookie->path = $path;
    $_SESSION["cookie.".$key] = $cookie;
}
function Cookie(string $key)
{
    if (array_key_exists("cookie.".$key, $_SESSION))
    {
        $cookie = $_SESSION["cookie.".$key];
        return $cookie->value;
    }
    else if (array_key_exists($key, $_COOKIE))
    {
        unset($_SESSION["cookie.$key"]);
        return $_COOKIE[$key];
    }
    return null;
}
function DeleteCookie(string $key, string $path)
{
    $cookie = new stdClass();
    $cookie->key = $key;
    $cookie->value = "";
    $cookie->expiration = -1;
    $cookie->path = $path;
    $_SESSION["cookie.".$key] = $cookie;
}
function SetSession($value = null, string $environment = null) : string
{
    $sessions = new Sessions();
    $sessions->str_session_key = $value;
    $sessionId = $sessions->GetSession();
    NewCookie("$environment.SessionId", $sessionId, "/$environment");
    NewCookie("$environment.SessionKey", $value, "/$environment");
    return $sessionId;
}
function CheckSession(string $environment = null) : bool
{
    if (Cookie("$environment.SessionId") != null)
    {
        $sessionValue = GetSession("admin");
        $sessions = new Sessions();
        $sessions->str_session_id = $sessionValue->SessionId;
        if (HasValue($sessionValue->SessionKey))
        {
            $sessions->str_session_key = $sessionValue->SessionKey;
        }
        return $sessions->CheckSession();
    }
    return false;
}
function GetSession(string $environment = null) : object
{
    $returnValue = new stdClass();;
    $returnValue->SessionId = Cookie("$environment.SessionId");
    $returnValue->SessionKey = Cookie("$environment.SessionKey");
    return $returnValue;
}
function DeleteSession(string $environment = null) : void
{
    DeleteCookie("$environment.SessionId", "/$environment");
    DeleteCookie("$environment.SessionKey", "/$environment");
}
?>