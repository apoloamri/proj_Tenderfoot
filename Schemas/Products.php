<?php
class Products extends Schema
{
    function __construct()
    {
        parent::__construct("products");
    }
    public $str_code;
    public $str_brand;
    public $str_name;
    public $str_description;
    public $dbl_price;
    public $dat_insert_time;
    public $dat_update_time;
    function CodeExists(string $code) : bool
    {
        $products = new Products();
        $products->str_code = $code;
        return $products->Exists("str_code");
    }
}
?>