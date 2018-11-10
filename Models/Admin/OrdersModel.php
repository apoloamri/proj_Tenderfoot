<?php
Model::AddSchema("Orders");
class OrdersModel extends Model
{   
    public $Id;
    function Validate() : iterable
    {
        if (HasValue($this->Id))
        {
            $orders = new Orders();
            if (!$orders->IdExists($this->Id))
            {
                yield "Id" => GetMessage("IdDoesNotExist", $this->Id);
            }
        }
    }
}
?>