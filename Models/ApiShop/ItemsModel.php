<?php
Model::AddSchema("Items");
class ItemsModel extends Model
{   
    public $count;
    public $result;
    public function Map()
    {
        $items = new Items();
        $items->AddOrderBy("dat_insert_time", DB::DESC);
        $items->Limit($this->count);
        $this->result = $items->Select();
    }
}
?>