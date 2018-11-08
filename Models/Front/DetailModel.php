<?php
Model::AddSchema("Products");
Model::AddSchema("ProductImages");
Model::AddSchema("ProductInventory");
class DetailModel extends Model
{   
    public $Code;
    public $Result;
    function Validate() : iterable
    {
        yield "Code" => $this->CheckInput("Code", true, Type::AlphaNumeric);
        if ($this->IsValid("Code"))
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
        $products->Join(new ProductInventory(), "int_product_id", "id");
        $products->str_code = $this->Code;
        $this->Result = $products->SelectSingle();
        $images = new ProductImages();
        $images->int_product_id = $products->id;
        $imageList = $images->GetImages();
        if (count($imageList) != 0)
        {
            $this->Result->ImagePaths = $images->GetImages($products->id);
        }
        else
        {
            $this->Result->ImagePaths[] = "";
        }
    }
}
?>