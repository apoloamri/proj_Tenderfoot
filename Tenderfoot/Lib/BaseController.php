<?php
class BaseController
{
    public $Model = null;
    protected function Validate() : void
    {
        $errorList = $this->Model->Validate();
        $errorList = iterator_to_array($errorList, true);
        $errorList = array_filter($errorList);
        if (count($errorList) > 0)
        {
            http_response_code(404);
            $this->Model->IsValid = false;
            $this->Model->Messages = $errorList;
        }
    }
}
?>