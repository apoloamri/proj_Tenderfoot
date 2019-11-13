<?php
class Carts extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("carts");
    }
    public $str_session_id;
    public $str_code;
    public $int_amount;
}
?>