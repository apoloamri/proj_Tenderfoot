<?php
class Admins extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("admins");
    }
    public $str_username;
    public $str_password;
    public $str_last_name;
    public $str_first_name;
    function HasAdmin() : bool
    {
        if ($this->Count("str_username") != 0)
        {
            return true;
        }
        return false;
    }
}
?>