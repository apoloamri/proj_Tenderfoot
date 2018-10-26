<?php
class ProductInventory extends Schema
{
    function __construct()
    {
        parent::__construct("product_inventory");
    }
    public $int_product_id;
    public $int_amount;
    public $dat_insert_time;
    public $dat_update_time;
}
?>