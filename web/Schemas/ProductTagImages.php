<?php
class ProductTagImages extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("product_tag_images");
    }
    public $str_tag;
    public $str_image_path;
}
?>