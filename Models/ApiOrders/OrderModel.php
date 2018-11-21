<?php
Model::AddSchema("Carts");
Model::AddSchema("Orders");
Model::AddSchema("OrderRecords");
Model::AddSchema("Products");
Model::AddSchema("ProductInventory");
class OrderModel extends Model
{   
    //GET
    public $Search;
    public $OrderStatus;
    public $Page;
    public $Count;
    public $Result;
    public $PageCount;
    //POST
    public $Id;
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
        else if ($this->Post())
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
        else if ($this->Put() || $this->Delete())
        {
            yield "Id" => $this->CheckInput("Id", false, Type::All);
            if ($this->IsValid("Id"))
            {
                $orders = new Orders();
                $orders->id = $this->Id;
                if (!$orders->Exists())
                {
                    yield "Id" => GetMessage("IdDoesNotExist");
                }
                else
                {
                    if ($this->Delete())
                    {
                        $orders->SelectSingle();
                        if ($orders->str_order_status == OrderStatus::Cancelled)
                        {
                            yield "Id" => GetMessage("InvalidOperation");
                        }
                    }
                }
            }
        }
    }
    function Map() : void
    {
        $orders = new Orders();
        if (HasValue($this->Id))
        {
            $orders->id = $this->Id;
            $this->Result = $orders->SelectSingle();
            $orderRecords = new OrderRecords();
            $orderRecords->int_order_id = $this->Id;
            $this->CartItems = $orderRecords->Select();
        }
        else
        {
            $orders->Where("str_order_number", DB::Like, "%".$this->Search."%", DB::OR);
            $orders->Where("str_last_name", DB::Like, "%".$this->Search."%", DB::OR);
            $orders->Where("str_first_name", DB::Like, "%".$this->Search."%");
            $orders->Combine(DB::AND);
            if (HasValue($this->OrderStatus))
            {
                $orders->Where("str_order_status", DB::Equal, $this->OrderStatus, DB::AND);
            }
            else
            {
                $orders->Where("str_order_status", DB::NotEqual, OrderStatus::Fulfilled, DB::AND);
                $orders->Where("str_order_status", DB::NotEqual, OrderStatus::Cancelled, DB::AND);
                $orders->Combine(DB::AND);
            }
            $orders->OrderBy("dat_insert_time", DB::DESC);
            $orders->Page((int)$this->Page, (int)$this->Count);
            $this->Result = $orders->Select();    
            $this->PageCount = $orders->PageCount((int)$this->Count);
        }
    }
    function Handle() : void
    {
        $now = Now();
        $orders = new Orders();
        if ($this->Post())
        {
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
                $orderRecords->int_product_id = $cartItem->{'products-id'};
                $orderRecords->dbl_total_price = (int)$orderRecords->int_amount * (int)$orderRecords->dbl_price;
                $orderRecords->dat_insert_time = $now;
                $orderRecords->dat_update_time = null;
                $orderRecords->Insert();
                $this->UpdateInventory((int)$orderRecords->int_product_id, -(int)$orderRecords->int_amount);
            }
            $carts->Delete();
            $this->OrderNumber = $orders->str_order_number;
            $this->SendEmail();
        }
        else if ($this->Put() || $this->Delete())
        {
            $orders->Where("id", DB::Equal, $this->Id);
            $orders->SelectSingle();
            if ($this->Put())
            {
                switch ($orders->str_order_status)
                {
                    case OrderStatus::NewOrder:
                    $orders->str_order_status = OrderStatus::Processed;
                    break;
                    case OrderStatus::Processed:
                    $orders->str_order_status = OrderStatus::OnDelivery;
                    break;
                    case OrderStatus::OnDelivery:
                    $orders->str_order_status = OrderStatus::Delivered;
                    break;
                    case OrderStatus::Delivered:
                    $orders->str_order_status = OrderStatus::Fulfilled;
                    break;
                }
            }
            else if ($this->Delete())
            {
                $orders->str_order_status = OrderStatus::Cancelled;
            }
            $orders->Update();
            $orderRecords = new OrderRecords();
            $orderRecords->int_order_id = $this->Id;
            $result = $orderRecords->Select();
            foreach ($result as $orderRecord)
            {
                $this->UpdateInventory((int)$orderRecord->int_product_id, (int)$orderRecord->int_amount);
            }
        }
    }
    function GetTotal($cartItems) : int
    {
        $price = 0;
        foreach ($cartItems as $item)
        {
            $price += $item->int_amount * $item->dbl_price;
        }
        return $price;
    }
    function UpdateInventory(int $productId, int $amount) : void
    {
        $inventory = new ProductInventory();
        $inventory->int_product_id = $productId;
        $inventory->SelectSingle();
        $inventory->int_amount = $inventory->int_amount + $amount;
        $inventory->dat_update_time = Now();
        $inventory->Where("int_product_id", DB::Equal, $productId);
        $inventory->Update(); 
    }
    function SendEmail() : void
    {
        $email = new Email($this, "OrderComplete");
        $email->SendEmail();
    }
}
?>