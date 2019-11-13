<?php
Model::AddSchema("Logs");
class LogsModel extends Model
{   
    public $Result;
    function Map() : void
    {
        $logs = new Logs();
        $logs->OrderBy("dat_insert_time", DB::DESC);
        $logs->Limit(10);
        $this->Result = $logs->Select();
    }
}
?>