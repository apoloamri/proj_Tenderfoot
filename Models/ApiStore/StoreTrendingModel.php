<?php
Model::AddSchema("Products");
Model::AddSchema("ProductImages");
Model::AddSchema("ProductInventory");
Model::AddSchema("ProductViews");
class StoreTrendingModel extends Model
{   
    public $Page;
    public $Count;
    public $Result;
    public $PageCount;
    function Validate() : iterable
    {
        yield "Page" => $this->CheckInput("Page", false, Type::Numeric);
        yield "Count" => $this->CheckInput("Count", false, Type::Numeric);
    }
    function Map() : void
    {
        $products = new Products();
        $products->Join(new ProductImages(), "int_product_id", "id");
        $products->Join(new ProductInventory(), "int_product_id", "id");
        $products->Join(new ProductViews(), "int_product_id", "id");
        $products->Where("product_views->dat_update_time", DB::GreaterThanEqual, Date::Now(-10080));
        $products->OrderBy("product_views->int_purchase", DB::DESC);
        $products->OrderBy("product_views->int_cart", DB::DESC);
        $products->OrderBy("product_views->int_view", DB::DESC);
        $products->GroupBy("id");
        $products->Page((int)$this->Page, (int)$this->Count);
        $this->Result = $products->Select();
        $this->PageCount = $products->PageCount((int)$this->Count);
    }
}
?>