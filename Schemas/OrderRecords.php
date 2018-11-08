<?php
class OrderRecords extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("order_records");
    }
    public $int_order_id;
    public $str_session_id;
    public $str_code;
    public $num_amount;
    public $dat_insert_time;
    public $dat_update_time;
}
?>