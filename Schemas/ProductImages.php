<?php
class ProductImages extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("product_images");
    }
    public $int_product_id;
    public $str_path;
    function GetImages() : array 
    {
        $return = array();
        $imageResult = $this->Select("str_path");
        foreach ($imageResult as $image)
        {
            $return[] = $image->str_path;
        }
        return $return;
    }
}
?>