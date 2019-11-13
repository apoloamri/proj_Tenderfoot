<?php
class ProductInventory extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("product_inventory");
    }
    public $int_product_id;
    public $int_amount;
}
?>