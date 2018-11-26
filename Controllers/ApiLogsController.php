<?php
require_once "BaseAdminController.php";
class ApiLogsController extends BaseAdminController
{
    function GetLogs(bool $showJson = true) : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("LogsModel");
        $this->Execute("GET");
        $this->Json("Result");
    }
}
?>