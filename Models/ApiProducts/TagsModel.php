<?php
Model::AddSchema("ProductTags");
class TagsModel extends Model
{   
    public $Result;
    function Map() : void
    {
        $tags = new ProductTags();
        $tags->OrderBy("str_tag");
        $this->Result = $tags->SelectDistinct("str_tag");
    }
}
?>