<?php
class SessionModel extends Model
{   
    function Map() : void
    {
        $valid = false;
        $sessionId = Session::Get()->SessionId;
        if (Obj::HasValue($sessionId))
        {
            $sessions = new Sessions();
            $sessions->str_session_id = $sessionId;
            $valid = $sessions->Session::Check();
        }
        if (!$valid)
        {
            Session::Set();
        }
    }
}
?>