<?php
Model::AddSchema("ProductTags");
Model::AddSchema("ProductTagImages");
class TagsModel extends Model
{   
    public $TagName;
    public $ImageFile;
    public $Result;
    function Validate() : iterable
    {
        if ($this->Post() || $this->Delete())
        {
            if ($this->Post())
            {
                yield "ImageFile" => $this->CheckInput("ImageFile", true, Type::Image);
            }
            yield "TagName" => $this->CheckInput("TagName", true, Type::AlphaNumeric);
        }
    }
    function Map() : void
    {
        $tags = new ProductTags();
        $tags->Join(new ProductTagImages(), "str_tag", "str_tag");
        $tags->OrderBy("str_tag");
        $tags->GroupBy("str_tag");
        $this->Result = $tags->Select("product_tags.id", "product_tags.str_tag", "str_image_path");
    }
    function Handle() : void
    {
        if ($this->Post())
        {
            $tempFile = $this->SaveTempFile($this->ImageFile);
            $fileUrl = $this->SaveFile($tempFile, "tags/".$this->TagName);
            $images = new ProductTagImages();
            $images->Where("str_tag", DB::Equal, $this->TagName);
            if (!$images->Exists())
            {
                $images->str_tag = $this->TagName;
                $images->str_image_path = $fileUrl;
                $images->Insert();
            }
            else
            {
                $images->str_image_path = $fileUrl;
                $images->Update();
            }
        }
        else if ($this->Delete())
        {
            $tags = new ProductTags();
            $tags->str_tag = $this->TagName;
            $tags->Delete();
        }
    }
}
?>