<?php
Model::AddSchema("Sessions");
class ItemsModel extends Model
{   
    public $count;
    public $session;
    public function Map() : void
    {
        $sessions = new Sessions();
        $sessions->str_session_id = GenerateRandomString(20);
        while ($sessions->Count() != 0)
        {
            $sessions->str_session_id = GenerateRandomString(20);
        }
        $sessions->Insert();
        $this->session = $sessions->str_session_id;
    }
}
?>