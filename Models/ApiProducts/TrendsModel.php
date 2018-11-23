<?php
Model::AddSchema("Products");
Model::AddSchema("ProductViews");
class TrendsModel extends Model
{   
    public $MostViews;
    public $MostCarts;
    public $MostPurchases;
    function Validate() : iterable
    {
        yield null;
    }
    function Map() : void
    {
        $views = new ProductViews();
        $views->Join(new Products(), "id", "int_product_id");
        $views->OrderBy("int_view", DB::DESC);
        $views->Limit(10);
        $this->MostViews = $views->Select();
        $views->Clear();
        $views->Join(new Products(), "id", "int_product_id");
        $views->OrderBy("int_cart", DB::DESC);
        $views->Limit(10);
        $this->MostCarts = $views->Select();
        $views->Clear();
        $views->Join(new Products(), "id", "int_product_id");
        $views->OrderBy("int_purchase", DB::DESC);
        $views->Limit(10);
        $this->MostPurchases = $views->Select();
        $views->Clear();
    }
}
?>