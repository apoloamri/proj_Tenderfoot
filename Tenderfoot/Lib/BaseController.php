<?php
class BaseController
{
    public $Model = null;
    public $Environment = "";
    protected function Validate() : void
    {
        $errorList = $this->Model->Validate();
        $errorList = iterator_to_array($errorList, true);
        $errorList = array_filter($errorList);
        if (count($errorList) > 0)
        {
            http_response_code(400);
            $this->Model->IsValid = false;
            $this->Model->Messages = $errorList;
        }
    }
    protected function SetTimeZone() : void
    {
        $timeZone = Settings::TimeZone();
        $sessions = new Sessions();
        $sessions->Execute("SET time_zone = '$timeZone';");
    }
    protected function Transact() : void
    {
        $sessions = new Sessions();
        $sessions->Execute("SET autocommit = OFF;");
        $sessions->Execute("START TRANSACTION;");
    }
    protected function Commit() : void
    {
        $sessions = new Sessions();
        $sessions->Execute("COMMIT;");
    }
}
?>