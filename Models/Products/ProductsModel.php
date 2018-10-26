<?php
Model::AddSchema("Products");
Model::AddSchema("ProductImages");
Model::AddSchema("ProductInventory");
class ProductsModel extends Model
{   
    //GET
    public $Search;
    public $Page;
    public $Count;
    public $Result;
    public $PageCount;
    //POST, PUT
    public $Id;
    public $Code;
    public $OldCode;
    public $Brand;
    public $Name;
    public $Description;
    public $Price;
    public $ImagePaths;
    function Validate() : iterable
    {
        if ($this->Get())
        {
            yield "Search" => $this->CheckInput("Search", false, Type::All);
            yield "Page" => $this->CheckInput("Page", false, Type::Numeric);
            yield "Count" => $this->CheckInput("Count", false, Type::Numeric);
        }
        if ($this->Post() || $this->Put())
        {
            if ($this->Put())
            {
                yield "Id" => $this->CheckInput("Id", true, Type::Numeric, 25);
                $this->OldCode = $this->Code;
            }
            yield "Code" => $this->CheckInput("Code", true, Type::AlphaNumeric, 25);
            yield "Brand" => $this->CheckInput("Brand", true, Type::All, 100);
            yield "Name" => $this->CheckInput("Name", true, Type::All, 100);
            yield "Description" => $this->CheckInput("Description", true, Type::All, 1000);
            yield "Price" => $this->CheckInput("Price", true, Type::Currency, 25);
            $hasValues = 
                HasValue($this->Code) &&
                HasValue($this->Brand) &&
                HasValue($this->Name) &&
                HasValue($this->Description) &&
                HasValue($this->Price);
            if ($hasValues)
            {
                $products = new Products();
                if ($products->CodeExists($this->Code, $this->OldCode))
                {
                    yield "Code" => GetMessage("CodeExists");
                }
            }
        }
        if ($this->Delete())
        {
            yield "Id" => $this->CheckInput("Id", true, Type::Numeric, 25);
        }
    }
    function Map() : void
    {
        $products = new Products();
        if (HasValue($this->Id))
        {
            $products->id = $this->Id;
            $this->Result = $products->SelectSingle();
            $images = new ProductImages();
            $images->int_product_id = $products->id;
            $imageResult = $images->Select("str_path");
            foreach ($imageResult as $image)
            {
                $this->ImagePaths[] = $image->str_path;
            }
        }
        else
        {
            $productImages = new ProductImages();
            $productInventory = new ProductInventory();
            $products->Join($productImages, "int_product_id", "id");
            $products->Join($productInventory, "int_product_id", "id");
            $products->Where("str_code", DB::Like, "%".$this->Search."%", DB::OR);
            $products->Where("str_name", DB::Like, "%".$this->Search."%", DB::OR);
            $products->Where("str_brand", DB::Like, "%".$this->Search."%");
            $products->GroupBy("id");
            $products->OrderBy("id", DB::DESC);
            $products->Page($this->Page, $this->Count);
            $this->Result = $products->Select();
            $this->PageCount = $products->PageCount($this->Count);
        }
    }
    function Handle() : void
    {
        $products = new Products();
        $products->str_code = $this->Code;
        $products->str_brand = $this->Brand;
        $products->str_name = $this->Name;
        $products->str_description = $this->Description;
        $products->dbl_price = $this->Price;
        if ($this->Post())
        {
            $products->dat_insert_time = Now();
            $products->Insert();
            $this->UpdateImages($products->id);
        }
        if ($this->Put())
        {
            $products->dat_update_time = Now();
            $products->Where("id", DB::Equal, $this->Id);
            $products->Update();
            $this->UpdateImages($this->Id);
        }
        if ($this->Delete())
        {
            $products->id = $this->Id;
            $products->Delete();
            $productImages = new ProductImages();
            $productImages->int_product_id = $this->Id;
            $productImages->Delete();
            $productInventory = new ProductInventory();
            $productInventory->int_product_id = $this->Id;
            $productInventory->Delete();
        }
    }
    function UpdateImages($id) : void
    {
        $productImages = new ProductImages();
        $productImages->int_product_id = $id;
        $productImages->Delete();
        foreach ($this->ImagePaths as $key => $value)
        {
            $productImages->id = null;
            $productImages->str_path = $this->SaveFile($value, $this->Code."-".$key);
            $productImages->Insert();
        }
    }
}
?>