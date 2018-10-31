<?php
Model::AddSchema("Products");
Model::AddSchema("ProductInventory");
class InventoryModel extends Model
{   
    public 
        $Id,
        $Amount;
    function Validate() : iterable
    {
        yield "Id" => $this->CheckInput("Id", false, Type::Numeric);
        yield "Amount" => $this->CheckInput("Amount", false, Type::Numeric);
        if (HasValue($this->Id))
        {
            $products = new Products();
            if (!$products->IdExists($this->Id))
            {
                yield "Id" => GetMessage("IdDoesNotExist", $this->Id);
            }
        }
    }
    function Handle() : void
    {
        $products = new ProductInventory();
        $products->int_product_id = $this->Id;
        if ($products->Exists())
        {
            $products->int_amount = $this->Amount;
            $products->dat_update_time = Now();
            $products->Where("int_product_id", DB::Equal, $this->Id);
            $products->Update();
        }
        else
        {
            $products->int_amount = $this->Amount;
            $products->dat_insert_time = Now();
            $products->Insert();
        }
    }
}
?>