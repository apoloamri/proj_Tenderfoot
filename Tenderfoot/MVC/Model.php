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
    function CheckInput(string $propertyName, bool $required = false, string $type = Type::All, int $length = 255) : string
    {
        $reflect = new ReflectionClass($this);
        $property = $reflect->getProperty($propertyName)->getValue($this);
        if ($property == null && $required)
        {
            return GetMessage("RequiredField", $propertyName);
        }
        if ($type == Type::Image)
        {
            $constants = new Image();
            $constants = new ReflectionClass(get_class($constants));
            $constants = array_values($constants->getConstants());
            if (!in_array($property["type"], $constants))
            {
                return GetMessage("InvalidFileType", $propertyName);
            }
        }
        else
        {
            if ($property != null)
            {
                if ($type != Type::All && !preg_match($type, $property)) {
                    return GetMessage("InvalidFieldInput", $propertyName);
                }
            }
            $validateLength = 
                is_string($property) && 
                (strlen($property) > $length) && 
                ($length != 0);
            if ($validateLength)
            {
                return GetMessage("InvalidFieldLength", $propertyName);
            }
        }
        return "";
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
    function SaveTempFile(array $fileArray) : string
    {
        $file = file_get_contents($fileArray["tmp_name"]);
        $fileType = $fileArray["type"];
        $fileExtension = "";
        switch ($fileType)
        {
            case Image::Tiff:
            case Image::XTiff:
                $fileExtension = ".tiff";
                break;
            case Image::Bmp:
            case Image::XBmp:
                $fileExtension = ".bmp";
                break;
            case Image::Gif:
                $fileExtension = ".gif";
                break;
            case Image::Icon:
                $fileExtension = ".ico";
                break;
            case Image::Jpeg:
            case Image::PJpeg:
                $fileExtension = ".jpg";
                break;
            case Image::Png:
                $fileExtension = ".png";
                break;
        }
        if ($fileExtension == "")
        {
            exit();
        }
        $fileName = pathinfo($fileArray["tmp_name"], PATHINFO_FILENAME);
        $urlPath = Settings::SiteUrl().Settings::FilePathTemp();
        $filePath = $_SERVER['DOCUMENT_ROOT'].parse_url($urlPath, PHP_URL_PATH);
        file_put_contents($filePath."/".$fileName.$fileExtension, $file);
        return $urlPath."/".$fileName.$fileExtension;
    }
    function SaveFile(string $fileUrl, string $fileName = null) : string
    {
        $urlPath = Settings::SiteUrl().Settings::FilePath();
        $newFilePath = $_SERVER['DOCUMENT_ROOT'].parse_url($urlPath, PHP_URL_PATH);
        $oldFilePath = $_SERVER['DOCUMENT_ROOT'].parse_url($fileUrl, PHP_URL_PATH);
        $fileExtension = pathinfo($oldFilePath, PATHINFO_EXTENSION);
        if ($fileName == null)
        {
            $fileName = pathinfo($oldFilePath, PATHINFO_FILENAME);
        }
        $newFilePath = $newFilePath."/".$fileName.".".$fileExtension;
        rename($oldFilePath, $newFilePath);
        return Settings::SiteUrl().Settings::FilePath()."/".$fileName.".".$fileExtension;
    }
    static function AddSchema(string $schemaName) : void
    {
        require_once "Schemas/$schemaName.php";
    }
} 
class Type
{
    const All = "";
    const Alphabet = "/^[a-zA-Z]*$/";
    const AlphaNumeric = "/^[a-zA-Z0-9]*$/";
    const Currency = "/^\d*\.?\d*$/";
    const Email = "^([a-zA-Z0-9_+\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$";
    const Numeric = "/^[0-9]*$/";
    const Url = "[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)";
    const Image = "Image";
}
class Image
{
    const Tiff = "image/tiff";
    const XTiff = "image/x-tiff";
    const Bmp = "image/bmp";
    const XBmp = "image/x-windows-bmp";
    const Gif = "image/gif";
    const Icon = "image/x-icon";
    const Jpeg = "image/jpeg";
    const PJpeg = "image/pjpeg";
    const Png = "image/png";
}
?>