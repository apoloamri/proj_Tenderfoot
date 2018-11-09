<?php
Model::AddSchema("Carts");
Model::AddSchema("Orders");
Model::AddSchema("OrderRecords");
Model::AddSchema("Products");
class OrderModel extends Model
{   
    //GET
    public $Search;
    public $SearchTag;
    public $Page;
    public $Count;
    public $Result;
    public $PageCount;
    //POST
    public $OrderNumber;
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
        if ($this->Get())
        {
            yield "Search" => $this->CheckInput("Search", false, Type::All);
            yield "Page" => $this->CheckInput("Page", false, Type::Numeric);
            yield "Count" => $this->CheckInput("Count", false, Type::Numeric);
        }
        if ($this->Post())
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
            yield "PhoneNumber" => $this->CheckInput("PhoneNumber", true, Type::PhoneNumber, 255);
            yield "LastName" => $this->CheckInput("LastName", true, Type::AlphaNumeric, 255);
            yield "FirstName" => $this->CheckInput("FirstName", true, Type::AlphaNumeric, 255);
            yield "Address" => $this->CheckInput("Address", true, Type::All, 255);
            yield "Barangay" => $this->CheckInput("Barangay", true, Type::AlphaNumeric, 255);
            yield "City" => $this->CheckInput("City", true, Type::AlphaNumeric, 255);
            yield "PostalCode" => $this->CheckInput("PostalCode", true, Type::All, 255);
        }
    }
    function Map() : void
    {
        $orders = new Orders();
        $orders->Where("str_order_number", DB::Like, "%".$this->Search."%", DB::OR);
        $orders->Where("str_last_name", DB::Like, "%".$this->Search."%", DB::OR);
        $orders->Where("str_first_name", DB::Like, "%".$this->Search."%");
        $orders->Page((int)$this->Page, (int)$this->Count);
        $this->Result = $orders->Select();
        $this->PageCount = $orders->PageCount((int)$this->Count);
    }
    function Handle() : void
    {
        $now = Now();
        $orders = new Orders();
        $orders->CreateOrderNumber();
        $orders->str_phonenumber = $this->PhoneNumber;
        $orders->str_last_name = $this->LastName;
        $orders->str_first_name = $this->FirstName;
        $orders->str_address = $this->Address;
        $orders->str_barangay = $this->Barangay;
        $orders->str_city = $this->City;
        $orders->str_postal = $this->PostalCode;
        $orders->str_order_status = OrderStatus::NewOrder;
        $orders->dat_insert_time = $now;
        $carts = new Carts();
        $carts->Join(new Products(), "str_code", "str_code");
        $carts->str_session_id = GetSession()->SessionId;
        $cartItems = $carts->Select();
        $orders->dbl_total = $this->GetTotal($cartItems);
        $orders->Insert();
        foreach ($cartItems as $cartItem)
        {
            $orderRecords = new OrderRecords();
            ModelOverwrite($orderRecords, $cartItem);
            $orderRecords->int_order_id = $orders->id;
            $orderRecords->dat_insert_time = $now;
            $orderRecords->Insert();
        }
        $carts->Delete();
        $this->OrderNumber = $orders->str_order_number;
        $this->SendEmail();
    }
    function GetTotal($cartItems) : int
    {
        $price = 0;
        foreach ($cartItems as $item)
        {
            $price += $item->num_amount * $item->dbl_price;
        }
        return $price;
    }
    function SendEmail() : void
    {
        $email = new Email($this, "OrderComplete");
        $email->SendEmail();
    }
}
?>