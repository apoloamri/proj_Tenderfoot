<?php
class CheckAuthModel extends Model
{   
    function Validate() : iterable
    {
        if (!CheckSession("admin"))
        {
            yield "Session" => GetMessage("InvalidAccess");
        }
        else
        {
            $session = GetSession("admin");
            if (!HasValue($session->SessionKey))
            {
                yield "Session" => GetMessage("InvalidAccess");
            }
        }
    }
}
?>