<?php
Model::AddSchema("Items");
class ItemModel extends Model
{   
    public $code;
    public $result;
    function Validate() : iterable
    {
        yield "code" => $this->CheckInput("code", true);
    }
    function Map() : void
    {
        $items = new Items();
        $items->str_code = $this->code;
        $this->result = $items->SelectSingle();
    }
}
?>