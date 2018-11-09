<?php
class Orders extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("orders");
    }
    public $str_order_number;
    public $str_phonenumber;
    public $str_last_name;
    public $str_first_name;
    public $str_address;
    public $str_barangay;
    public $str_city;
    public $str_postal;
    public $dbl_total;
    public $str_order_status;
    public $dat_insert_time;
    public $dat_update_time;
    function CreateOrderNumber()
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
}
class OrderStatus
{
    const NewOrder = "New Order";
    const ReceivedOrder = "Received Order";
    const OrderOnDelivery = "Order On Delivery";
    const OrderDelivered = "Order Delivered";
    const OrderFulfilled = "Order Fulfilled";
}
?>