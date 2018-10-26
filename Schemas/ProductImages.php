<?php
class ProductImages extends Schema
{
    function __construct()
    {
        parent::__construct("product_images");
    }
    public $int_product_id;
    public $str_path;
    public $dat_insert_time;
}
?>