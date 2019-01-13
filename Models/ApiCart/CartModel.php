<?php
Model::AddSchema("Carts");
Model::AddSchema("Products");
Model::AddSchema("ProductImages");
Model::AddSchema("ProductViews");
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
        if (_::HasValue($sessionId))
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
        $carts->Join($productImages, "int_product_id", "products->id");
        $carts->str_session_id = GetSession()->SessionId;
        $carts->GroupBy("id");
        $this->Result = $carts->Select();
        $amount = 0;
        $price = 0;
        foreach ($this->Result as $item)
        {
            $amount += $item->int_amount;
            $price += $item->int_amount * (
                $item->dbl_sale_price != null && $item->dbl_sale_price != 0 ? 
                $item->dbl_sale_price : 
                $item->dbl_price);
        }
        $this->Count = $amount;
        $this->Total = $price;
    }
    function Handle() : void
    {
        $sessionId = GetSession()->SessionId;
        $carts = new Carts();
        if ($this->Post() || $this->Put())
        {
            $carts->str_code = $this->Code;
            $carts->str_session_id = $sessionId;
            if ($carts->Count() > 0)
            {
                $carts->SelectSingle();
                $carts->int_amount = 
                    _::HasValue($this->Amount) ? 
                    $this->Amount : 
                    $carts->int_amount + 1;
                $carts->Where("str_code", DB::Equal, $this->Code);
                $carts->Where("str_session_id", DB::Equal, $sessionId);
                $carts->Update();
            }
            else
            {
                $carts->int_amount = 
                    _::HasValue($this->Amount) ? 
                    $this->Amount : 
                    1;
                $carts->Insert();
                $products = new Products();
                $products->str_code = $this->Code;
                $products->SelectSingle();
                $views = new ProductViews();
                $views->int_product_id = $products->id;
                $views->AddCart();
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