<?php
Model::AddSchema("ProductTags");
class TagsModel extends Model
{   
    public $Result;
    function Validate() : iterable
    {
        yield null;
    }
    function Map() : void
    {
        $tags = new ProductTags();
        $tags->OrderBy("str_tag");
        $this->Result = $tags->Select("DISTINCT(str_tag)");
    }
}
?>