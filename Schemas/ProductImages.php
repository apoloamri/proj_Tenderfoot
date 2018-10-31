<?php
class ProductImages extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("product_images");
    }
    public $int_product_id;
    public $str_path;
    public $dat_insert_time;
    function GetImages($productId) : array 
    {
        $return = array();
        $images = new ProductImages();
        $images->int_product_id = $productId;
        $imageResult = $images->Select("str_path");
        foreach ($imageResult as $image)
        {
            $return[] = $image->str_path;
        }
        return $return;
    }
}
?>