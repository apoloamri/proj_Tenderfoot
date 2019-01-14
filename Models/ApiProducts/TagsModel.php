<?php
Model::AddSchema("Products");
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
        if (_::HasValue($this->TagName))
        {
            $tags->Where("str_tag", DB::Like, "%$this->TagName%");
        }
        $tags->OrderBy("str_tag");
        $tags->GroupBy("str_tag");
        $result = $tags->Select("id", "str_tag", "product_tag_images->str_image_path");
        foreach ($result as $item)
        {
            $subTags = new ProductTags();
            $subTags->str_tag = $item->str_tag;
            $products = new Products();
            $products->InQuery("id", $subTags, "int_product_id");
            $item->products = $products->Select();
        }
        $this->Result = $result;
    }
    function Handle() : void
    {
        if ($this->Post() || $this->Put())
        {
            $images = new ProductTagImages();
            $images->Where("str_tag", DB::Equal, $this->TagName);
            if ($this->Post())
            {
                $tempFile = $this->SaveTempFile($this->ImageFile);
                $fileUrl = $this->SaveFile($tempFile, "tags/".$this->TagName);
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
            else if ($this->Put())
            {
                $images->Delete();
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