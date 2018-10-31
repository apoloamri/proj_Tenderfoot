<?php
class Users extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("users");
    }
    public $str_username;
    public $str_password;
    public $str_last_name;
    public $str_first_name;
    public $dat_insert_time;
    public $dat_update_time;
}
?>