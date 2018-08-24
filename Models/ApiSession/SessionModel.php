<?php
class SessionModel extends Model
{   
    public $session;
    public function Map() : void
    {
        $sessions = new Sessions();
        $sessions->str_session_id = $this->session;
        $this->session = $sessions->GetSession();     
    }
}
?>