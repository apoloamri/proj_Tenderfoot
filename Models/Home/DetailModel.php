<?php
Model::AddSchema("Products");
Model::AddSchema("ProductImages");
class DetailModel extends Model
{   
    public $Code;
    public $Result;
    function Validate() : iterable
    {
        yield "Code" => $this->CheckInput("Code", true, Type::AlphaNumeric);
        if (HasValue($this->Code))
        {
            $products = new Products();
            if (!$products->CodeExists($this->Code))
            {
                yield "Code" => GetMessage("CodeNotFound");
            }
        }
    }
    function Map() : void
    {
        $products = new Products();
        $products->str_code = $this->Code;
        $this->Result = $products->SelectSingle();
        $images = new ProductImages();
        $this->Result->ImagePaths = $images->GetImages($products->id);
    }
}
?>