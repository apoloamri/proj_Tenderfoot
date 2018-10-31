<?php
class CheckAuthModel extends Model
{   
    public 
        $sessionKey,
        $sessionId;
    function Validate() : iterable
    {
        if (array_key_exists("admin.session_key", $_SESSION))
        {
            $this->sessionKey = $_SESSION["admin.session_key"];
        }
        if (array_key_exists("admin.session_id", $_SESSION))
        {
            $this->sessionId = $_SESSION["admin.session_id"];
        }
        $hasValues = 
            HasValue($this->sessionKey) && 
            HasValue($this->sessionId);
        if ($hasValues)
        {
            $sessions = new Sessions();
            $sessions->str_session_key = $this->sessionKey;
            $sessions->str_session_id = $this->sessionId;
            if (!$sessions->CheckSession())
            {
                yield "Session" => GetMessage("InvalidAccess");
            }
        }
        else
        {
            yield "Session" => GetMessage("InvalidAccess");
        }
    }
}
?>