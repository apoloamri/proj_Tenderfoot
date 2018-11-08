<?php
class Orders extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("orders");
    }
    public $str_phonenumber;
    public $str_last_name;
    public $str_first_name;
    public $str_address;
    public $str_barangay;
    public $str_city;
    public $str_postal;
    public $dbl_total;
    public $dat_insert_time;
    public $dat_update_time;
}
?>