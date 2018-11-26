<?php
Model::AddSchema("Products");
Model::AddSchema("ProductInventory");
Model::AddSchema("Logs");
class InventoryModel extends Model
{   
    public $Id;
    public $Amount;
    function Validate() : iterable
    {
        yield "Id" => $this->CheckInput("Id", false, Type::Numeric);
        if ($this->IsValid("Id"))
        {
            $products = new Products();
            if (!$products->IdExists($this->Id))
            {
                yield "Id" => GetMessage("IdDoesNotExist", $this->Id);
            }
        }
        yield "Amount" => $this->CheckInput("Amount", false, Type::Numeric);
        if ($this->IsValid("Amount"))
        {
            if ((int)$this->Amount < 0)
            {
                yield "Amount" => GetMessage("InvalidFieldInput", "Amount");
            }
        }
    }
    function Handle() : void
    {
        $logs = new Logs();
        $productInventory = new ProductInventory();
        $productInventory->int_product_id = $this->Id;
        if ($productInventory->Exists())
        {
            $productInventory->Where("int_product_id", DB::Equal, $this->Id);
            $productInventory->SelectSingle();
            $logs->str_action = 
                $productInventory->int_amount < $this->Amount ?
                Action::Increased :
                Action::Decreased;
            $productInventory->int_amount = $this->Amount;
            $productInventory->Update();
        }
        else
        {
            $productInventory->int_amount = $this->Amount;
            $productInventory->Insert();
            $logs->str_action = Action::Increased;
        }
        $products = new Products();
        $products->id = $this->Id;
        $products->SelectSingle();
        $logs->str_code = $products->str_code;
        $logs->LogAction();
    }
}
?>