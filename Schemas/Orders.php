<?php
class Orders extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("orders");
    }
    public $str_order_number;
    public $str_phonenumber;
    public $str_email;
    public $str_last_name;
    public $str_first_name;
    public $str_address;
    public $str_barangay;
    public $str_city;
    public $str_postal;
    public $dbl_total;
    public $str_order_status;
    function CreateOrderNumber() : void
    {
        $order = new Orders();
        $order->str_order_number = GenerateRandomString(10);
        while ($order->Count() != 0)
        {
            $order->Clear();
            $order->str_order_number = GenerateRandomString(10);
        }
        $this->str_order_number = $order->str_order_number;
    }
    function IdExists(int $id) : bool
    {
        $orders = new Orders();
        $orders->id = $id;
        return $orders->Exists();
    }
}
class OrderStatus
{
    const NewOrder = "New Order";
    const Processed = "Processed";
    const OnDelivery = "On Delivery";
    const Delivered = "Delivered";
    const Fulfilled = "Fulfilled";
    const Cancelled = "Cancelled";
}
?>