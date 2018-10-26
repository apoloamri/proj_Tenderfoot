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
    function IdExists(int $id) : bool
    {
        $products = new Products();
        $products->id = $id;
        return $products->Exists();
    }
    function CodeExists(string $code, string $oldCode = null) : bool
    {
        $products = new Products();
        $products->str_code = $code;
        if (HasValue($oldCode))
        {
            $products->Where("str_code", DB::NotEqual, $oldCode);
        }
        return $products->Exists("str_code");
    }
}
?>