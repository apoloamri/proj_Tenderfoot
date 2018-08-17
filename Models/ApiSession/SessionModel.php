<?php
Model::AddSchema("Sessions");
class ItemsModel extends Model
{   
    public $count;
    public $session;
    public function Map()
    {
        $sessions = new Sessions();
        $this->session = "";
    }
}
?>