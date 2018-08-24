<?php
Model::AddSchema("Items");
class ItemModel extends Model
{   
    public $code;
    public $result;
    public function Validate() : iterable
    {
        yield "code" => $this->Required("code");
    }
    public function Map() : void
    {
        $items = new Items();
        $items->str_code = $this->code;
        $this->result = $items->SelectSingle();
    }
}
?>