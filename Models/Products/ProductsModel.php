<?php
Model::AddSchema("Products");
Model::AddSchema("ProductImages");
class ProductsModel extends Model
{   
    //GET
    public $Search;
    public $Page;
    public $Count;
    public $Result;
    public $PageCount;
    //POST
    public $Id;
    public $Code;
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
                if ($products->CodeExists($this->Code))
                {
                    yield "Code" => GetMessage("CodeExists");
                }
            }
        }
    }
    function Map() : void
    {
        $products = new Products();
        $products->Where("str_name", DB::Like, "%".$this->Search."%", DB::OR);
        $products->Where("str_code", DB::Like, "%".$this->Search."%");
        $products->OrderBy("id", DB::DESC);
        $products->Page($this->Page, $this->Count);
        $this->Result = $products->Select();
        $this->PageCount = $products->PageCount($this->Count);
    }
    function Handle() : void
    {
        $products = new Products();
        $products->str_code = $this->Code;
        $products->str_brand = $this->Brand;
        $products->str_name = $this->Name;
        $products->str_description = $this->Description;
        $products->dbl_price = $this->Price;
        $products->dat_insert_time = Now();
        if ($this->Post())
        {
            $products->Insert();
            foreach ($this->ImagePaths as $key => $value)
            {
                $productImages = new ProductImages();
                $productImages->str_code = $this->Code;
                $productImages->str_path = $this->SaveFile($value, $this->Code."-".$key);
                $productImages->Insert();
            }
        }
        if ($this->Put())
        {
            $products->Where("id", DB::Equal, $this->Id);
            $products->Update();
        }
    }
}
?>