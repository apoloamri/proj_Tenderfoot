<?php
class SessionModel extends Model
{   
    function Map() : void
    {
        $valid = false;
        $sessionId = GetSession()->SessionId;
        if (Obj::HasValue($sessionId))
        {
            $sessions = new Sessions();
            $sessions->str_session_id = $sessionId;
            $valid = $sessions->CheckSession();
        }
        if (!$valid)
        {
            SetSession();
        }
    }
}
?>