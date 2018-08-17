<?php
require_once "Tenderfoot/Lib/BaseModel.php";
class Model extends BaseModel
{
    public $IsValid = true;
    public $Messages = null, $URI = null;
    function SiteUrl() : string { return Settings::SiteUrl(); }
    function SiteUrlSSL() : string { return Settings::SiteUrlSSL(); }
    function Get() : bool { return ($_SERVER['REQUEST_METHOD'] == "GET"); }
    function Post() : bool { return ($_SERVER['REQUEST_METHOD'] == "POST"); }
    function Put() : bool { return ($_SERVER['REQUEST_METHOD'] == "PUT"); }
    function Delete() : bool { return ($_SERVER['REQUEST_METHOD'] == "DELETE"); }
    function Validate() : iterable { yield null; }
    function Map() : void { }
    function Handle() : void { }
    function Required(string $propertyName) : string
    {
        $reflect = new ReflectionClass($this);
        $property = $reflect->getProperty($propertyName)->getValue($this);
        if ($property == null)
        {
            return GetMessage("RequiredField", $propertyName);
        }
    }
    private $ControllerName, $ViewName;
    function InitiatePage(string $controller, string $viewName) : void
    {
        $this->ControllerName = str_replace("Controller", "", $controller);
        $this->ViewName = $viewName;
    }
    function RenderPage() : void
    {
        $view = "Views/$this->ControllerName/$this->ViewName.php";
        if (file_exists($view))
		{
			header("Content-Type: text/html");
			require_once $view;
		}
    }
    function Partial(string $partialView) : void
    {
        $view = "Views/$this->ControllerName/$partialView.php";
        if (file_exists($view))
		{
			header("Content-Type: text/html");
			require_once $view;
        }
        else
        {
            $view = "Views/Partial/$partialView.php";
            if (file_exists($view))
            {
                header("Content-Type: text/html");
                require_once $view;
            }
        }
    }
    static function AddSchema(string $schemaName) : void
    {
        require_once "Schemas/$schemaName.php";
    }
} 
?>