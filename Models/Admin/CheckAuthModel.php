<?php
class CheckAuthModel extends Model
{   
    public $sessionKey;
    public $sessionId;
    function Validate() : iterable
    {
        $this->sessionKey = $_SESSION["admin.session_key"];
        $this->sessionId = $_SESSION["admin.session_id"];
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
    }
}
?>