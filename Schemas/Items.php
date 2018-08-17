<?php
class Items extends Schema
{
    function __construct()
    {
        parent::__construct("items");
    }
    public $str_code;
    public $str_brand;
    public $str_name;
    public $str_description;
    public $str_image_url;
    public $dbl_price;
    public $num_category;
    public $dat_insert_time;
    public $dat_update_time;
}
?>