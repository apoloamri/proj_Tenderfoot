<?php
class AuthorizeModel extends Model
{   
    function Validate() : iterable
    {
        if (!Session::Check("backoffice"))
        {
            yield "session" => GetMessage("InvalidAccess");
        }
        else
        {
            $session = Session::Get("backoffice");
            if (!Obj::HasValue($session->SessionKey))
            {
                yield "session" => GetMessage("InvalidAccess");
            }
        }
    }
}
?>