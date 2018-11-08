<?php
Model::AddSchema("Carts");
Model::AddSchema("Orders");
class OrderModel extends Model
{   
    public $PhoneNumber;
    public $LastName;
    public $FirstName;
    public $Address;
    public $Barangay;
    public $City;
    public $PostalCode;
    public $CartItems;
    public $Total;
    function Validate() : iterable
    {
        $sessionId = GetSession()->SessionId;
        if (HasValue($sessionId))
        {
            $carts = new Carts();
            $carts->str_session_id = $sessionId;
            if (!$carts->Exists())
            {
                yield "sessionId" => GetMessage("InvalidAccess");
            }
        }
        if ($this->Post())
        {
            yield "PhoneNumber" => $this->CheckInput("PhoneNumber", true, Type::PhoneNumber, 255);
            yield "LastName" => $this->CheckInput("LastName", true, Type::AlphaNumeric, 255);
            yield "FirstName" => $this->CheckInput("FirstName", true, Type::AlphaNumeric, 255);
            yield "Address" => $this->CheckInput("Address", true, Type::All, 255);
            yield "Barangay" => $this->CheckInput("Barangay", true, Type::AlphaNumeric, 255);
            yield "City" => $this->CheckInput("City", true, Type::AlphaNumeric, 255);
            yield "PostalCode" => $this->CheckInput("PostalCode", true, Type::All, 255);
        }
    }
    function Handle() : void
    {
        $now = Now();
        $orders = new Orders();
        $orders->str_phonenumber = $this->PhoneNumber;
        $orders->str_last_name = $this->LastName;
        $orders->str_first_name = $this->FirstName;
        $orders->str_address = $this->Address;
        $orders->str_barangay = $this->Barangay;
        $orders->str_city = $this->City;
        $orders->str_postal = $this->PostalCode;
        $orders->dat_insert_time = $now;
        $orders->Insert();
        $carts = new Carts();
        $carts->str_session_id = GetSession()->SessionId;
        $cartItems = $carts->Select();
        foreach ($cartItems as $cartItem)
        {
            $orderRecords = new OrderRecords();
            ModelOverwrite($orderRecords, $cartItems);
            $orderRecords->int_order_id = $orders->id;
            $orderRecords->dat_insert_time = $now;
            $orderRecords->Insert();
        }
        $carts->Delete();
    }
}
?>