<?php
Model::AddSchema("Products");
Model::AddSchema("ProductImages");
Model::AddSchema("ProductInventory");
Model::AddSchema("ProductViews");
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
        $this->GetProduct();
        $this->GetImages();
        $this->AddView();
    }
    function GetProduct() : void
    {
        $products = new Products();
        $products->Join(new ProductInventory(), "int_product_id", "id");
        $products->str_code = $this->Code;
        $this->Result = $products->SelectSingle();
    }
    function GetImages() : void
    {
        $images = new ProductImages();
        $images->int_product_id = $this->Result->id;
        if ($images->Exists())
        {
            $this->Result->ImagePaths = $images->GetImages();
        }
        else
        {
            $this->Result->ImagePaths[] = "";
        }
    }
    function AddView() : void
    {
        $cookie = Cookie("History");
        if (!_::StringContains($this->Code, $cookie))
        {
            NewCookie("History", $cookie.$this->Code." ");
            $views = new ProductViews();
            $views->int_product_id = $this->Result->id;
            $views->AddView();
        }
    }
}
?>