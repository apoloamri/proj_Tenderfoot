<?php
Model::AddSchema("Orders");
Model::AddSchema("OrderRecords");
Model::AddSchema("Products");
Model::AddSchema("ProductImages");
class TrackingModel extends Model
{   
    public $OrderNumber;
    public $Result;
    public $CartItems;
    function Validate() : iterable
    {
        yield "OrderNumber" => $this->CheckInput("OrderNumber", true, Type::AlphaNumeric);
        if ($this->IsValid("OrderNumber"))
        {
            $orders = new Orders();
            $orders->str_order_number = $this->OrderNumber;
            $orders->Where("str_order_status", DB::NotEqual, OrderStatus::Fulfilled, DB::AND);
            $orders->Where("str_order_status", DB::NotEqual, OrderStatus::Cancelled);
            if (!$orders->Exists())
            {
                yield "OrderNumber" => GetMessage("InvalidAccess");
            }
        }
    }
    function Map() : void
    {
        $orders = new Orders();
        $orders->str_order_number = $this->OrderNumber;
        $this->Result = $orders->SelectSingle();
        $orderRecords = new OrderRecords();
        $products = new Products();
        $productImages = new ProductImages();
        $orderRecords->Join($products, "str_code", "str_code");
        $orderRecords->Join($productImages, "int_product_id", "products.id");
        $orderRecords->int_order_id = $orders->id;
        $this->CartItems = $orderRecords->Select();
    }
}
?>