<?php
class Store extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("store");
        if (!$this->Exists())
        {
            $this->Insert();
        }        
    }
    public $str_announcement;
    public $str_header;
}
?>