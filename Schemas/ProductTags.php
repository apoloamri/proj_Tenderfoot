<?php
class ProductTags extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("product_tags");
    }
    public $int_product_id;
    public $str_tag;
    public $dat_insert_time;
    function GetTags($productId) : string
    {
        $tagArray = array();
        $tags = new ProductTags();
        $tags->int_product_id = $productId;
        $tagResult = $tags->Select("str_tag");
        foreach ($tagResult as $tag)
        {
            $tagArray[] = $tag->str_tag;
        }
        if (count($tagArray) > 0)
        {
            return join(", ", $tagArray);
        }
        return "";
    }
}
?>