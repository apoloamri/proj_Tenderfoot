<?php
Model::AddSchema("Carts");
Model::AddSchema("Products");
Model::AddSchema("ProductImages");
class CartModel extends Model
{   
    public $Code;
    public $Amount;
    public $Result;
    public $Count = 0;
    public $Total = 0;
    function Validate() : iterable
    {
        $sessionId = GetSession()->SessionId;
        if (HasValue($sessionId))
        {
            $sessions = new Sessions();
            $sessions->str_session_id = $sessionId;
            yield "sessionId" => $sessions->ValidateSession();
        }
        if ($this->Post() || $this->Delete())
        {
            yield "Code" => $this->CheckInput("Code", true);
            if ($this->Put())
            {
                yield "Amount" => $this->CheckInput("Amount", true);
            }
        }
    }
    function Map() : void
    {
        $carts = new Carts();
        $products = new Products();
        $productImages = new ProductImages();
        $carts->Join($products, "str_code", "str_code");
        $carts->Join($productImages, "int_product_id", "products.id");
        $carts->str_session_id = GetSession()->SessionId;
        $carts->GroupBy("id");
        $this->Result = $carts->Select();
        $amount = 0;
        $price = 0;
        foreach ($this->Result as $item)
        {
            $amount += $item->num_amount;
            $price += $item->num_amount * $item->dbl_price;
        }
        $this->Count = $amount;
        $this->Total = $price;
    }
    function Handle() : void
    {
        $sessionId = GetSession()->SessionId;
        $carts = new Carts();
        if ($this->Post())
        {
            $carts->str_code = $this->Code;
            $carts->str_session_id = $sessionId;
            if ($carts->Count() > 0)
            {
                $carts->SelectSingle();
                $carts->num_amount = 
                    HasValue($this->Amount) ? 
                    $this->Amount : 
                    $carts->num_amount + 1;
                $carts->dat_update_time = Now();
                $carts->Where("str_code", DB::Equal, $this->Code);
                $carts->Where("str_session_id", DB::Equal, $sessionId);
                $carts->Update();
            }
            else
            {
                $carts->num_amount = 
                    HasValue($this->Amount) ? 
                    $this->Amount : 
                    1;
                $carts->dat_insert_time = Now();
                $carts->Insert();
            }
        }
        else if ($this->Delete())
        {
            $carts->str_code = $this->Code;
            $carts->str_session_id = $sessionId;
            $carts->Delete();
        }
    }
}
?>