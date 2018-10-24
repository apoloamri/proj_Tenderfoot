<?php
class ProductImages extends Schema
{
    function __construct()
    {
        parent::__construct("product_images");
    }
    public $str_code;
    public $str_path;
    public $dat_insert_time;
    public $dat_update_time;
}
?>