<?php
class Products extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("products");
    }
    public $str_code;
    public $str_brand;
    public $str_name;
    public $txt_description;
    public $dbl_price;
    public $dbl_sale_price;
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
        if (Obj::HasValue($oldCode))
        {
            $products->Where("str_code", DB::NotEqual, $oldCode);
        }
        return $products->Exists("str_code");
    }
}
?>