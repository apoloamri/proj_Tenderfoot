<?php
Model::AddSchema("ProductTags");
class TagsModel extends Model
{   
    public $TagName;
    public $Result;
    function Validate() : iterable
    {
        yield null;
        if ($this->Delete())
        {
            yield "TagName" => $this->CheckInput("TagName", true, Type::AlphaNumeric);
        }
    }
    function Map() : void
    {
        $tags = new ProductTags();
        $tags->OrderBy("str_tag");
        $this->Result = $tags->SelectDistinct("str_tag");
    }
    function Handle() : void
    {
        if ($this->Delete())
        {
            $tags = new ProductTags();
            $tags->str_tag = $this->TagName;
            $tags->Delete();
        }
    }
}
?>