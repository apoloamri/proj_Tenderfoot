<?php
require_once "Tenderfoot/Lib/BaseView.php";
class View extends BaseView
{
    function __construct($controller, $model, string $view)
    {
        $this->Controller = $controller;
        $this->Model = $model;
        $this->ViewFile = $view;
        $this->CompileView();
        if (_::HasValue($this->View))
        {
            $this->GetRenders();
            $this->GetValues($this->Model);
            $this->GetTitle();
        }
        else
        {
            $this->NotFound = true;
        }
    }
    private function CompileView() : void
    {
        $controllerName =  str_replace("Controller", "", get_class($this->Controller));
        $this->View = 
            file_exists("Views/$this->ViewFile.html") ? 
            file_get_contents("Views/$this->ViewFile.html") :
            "";
        if (!_::HasValue($this->View))
        {
            $this->View = 
                file_exists("Views/$controllerName/$this->ViewFile.html") ?
                file_get_contents("Views/$controllerName/$this->ViewFile.html") :
                "";
        }
        if (!_::HasValue($this->View))
        {
            $this->View = 
                file_exists("Views/Partial/$this->ViewFile.html") ?
                file_get_contents("Views/Partial/$this->ViewFile.html") :
                "";
        }
        if (!_::HasValue($this->View))
        {
            $this->View = 
                file_exists("Emails/$this->ViewFile.html") ?
                file_get_contents("Emails/$this->ViewFile.html") :
                "";
        }
    }
}
?>