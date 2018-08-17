<?php
require_once "Tenderfoot/Lib/BaseModel.php";
class Model extends BaseModel
{
    public $IsValid = true;
    public $Messages = null, $URI = null;
    function SiteUrl() { return Settings::SiteUrl(); }
    function SiteUrlSSL() { return Settings::SiteUrlSSL(); }
    function Get() { return ($_SERVER['REQUEST_METHOD'] == "GET"); }
    function Post() { return ($_SERVER['REQUEST_METHOD'] == "POST"); }
    function Put() { return ($_SERVER['REQUEST_METHOD'] == "PUT"); }
    function Delete() { return ($_SERVER['REQUEST_METHOD'] == "DELETE"); }
    function Validate() { yield null; }
    function Map() { }
    function Handle() { }
    function Required(string $propertyName)
    {
        $reflect = new ReflectionClass($this);
        $property = $reflect->getProperty($propertyName)->getValue($this);
        if ($property == null)
        {
            return GetMessage("RequiredField", $propertyName);
        }
    }
    private $ControllerName, $ViewName;
    function InitiatePage(string $controller, string $viewName)
    {
        $this->ControllerName = str_replace("Controller", "", $controller);
        $this->ViewName = $viewName;
    }
    function RenderPage()
    {
        $view = "Views/$this->ControllerName/$this->ViewName.php";
        if (file_exists($view))
		{
			header("Content-Type: text/html");
			require_once $view;
		}
    }
    function Partial(string $partialView)
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
    static function AddSchema(string $schemaName)
    {
        require_once "Schemas/$schemaName.php";
    }
} 
?>