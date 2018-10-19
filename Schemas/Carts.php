<?php
class Carts extends Schema
{
    function __construct()
    {
        parent::__construct("carts");
    }
    public $str_session_id;
    public $str_code;
    public $num_amount;
    public $dat_insert_time;
    public $dat_update_time;
}
?>