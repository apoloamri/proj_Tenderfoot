<?php
Model::AddSchema("Carts");
Model::AddSchema("Items");
class CartModel extends Model
{   
    public $itemCode;
    public $sessionId;
    public $result;
    public $count = 0;
    public $total = 0;
    function Validate() : iterable
    {
        yield "sessionId" => $this->Required("sessionId");
        if (HasValue($this->sessionId))
        {
            $sessions = new Sessions();
            $sessions->str_session_id = $this->sessionId;
            yield "sessionId" => $sessions->ValidateSession();
        }
        if ($this->Post() || $this->Delete())
        {
            yield "itemCode" => $this->Required("itemCode");
        }
    }
    function Map() : void
    {
        $cart = new Carts();
        $items = new Items();
        $cart->Join($items, "str_code");
        $cart->str_session_id = $this->sessionId;
        $this->result = $cart->Select();
        $compute = $cart->SelectSingle(
            "SUM(num_amount) AS amount", 
            "SUM(dbl_price) AS price");
        $this->count = $compute->amount ?? 0;
        $this->total = $compute->price ?? 0;
    }
    function Handle() : void
    {
        $cart = new Carts();
        if ($this->Post())
        {
            $cart->str_code = $this->itemCode;
            $cart->str_session_id = $this->sessionId;
            if ($cart->Count() > 0)
            {
                $cart->SelectSingle();
                $cart->num_amount = $cart->num_amount + 1;
                $cart->dat_update_time = Now();
                $cart->Where("str_code", DB::Equal, $this->itemCode);
                $cart->Where("str_session_id", DB::Equal, $this->sessionId);
                $cart->Update();
            }
            else
            {
                $cart->num_amount = 1;
                $cart->dat_insert_time = Now();
                $cart->Insert();
            }
        }
        else if ($this->Delete())
        {
            $cart->str_code = $this->itemCode;
            $cart->str_session_id = $this->sessionId;
            $cart->Delete();
        }
    }
}
?>