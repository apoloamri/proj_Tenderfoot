<?php
class ProductViews extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("product_views");
    }
    public $int_product_id;
    public $int_view;
    public $int_cart;
    public $int_purchase;
    function GetView()
    {
        $views = new ProductViews();
        $views->Where("int_product_id", DB::Equal, $this->int_product_id);
        if (!$views->ExistsOne())
        {
            $views->int_product_id = $this->int_product_id;
            $views->int_view = 0;
            $views->int_cart = 0;
            $views->int_purchase = 0;
            $views->Insert();
            return $views;
        }
        else
        {
            $views->SelectSingle();
            return $views;
        }
    }
    function AddView() : void
    {
        $views = $this->GetView();
        $views->int_view = $views->int_view + 1;
        $views->Update();
    }
    function AddCart() : void
    {
        $views = $this->GetView();
        $views->int_cart = $views->int_cart + 1;
        $views->Update();
    }
    function AddPurchase() : void
    {
        $views = $this->GetView();
        $views->int_purchase = $views->int_purchase + 1;
        $views->Update();
    }
}
?>