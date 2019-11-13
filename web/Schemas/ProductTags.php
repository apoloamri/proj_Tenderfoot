<?php
class ProductTags extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("product_tags");
    }
    public $int_product_id;
    public $str_tag;
    function GetTags() : string
    {
        $tagArray = array();
        $tagResult = $this->Select("str_tag");
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