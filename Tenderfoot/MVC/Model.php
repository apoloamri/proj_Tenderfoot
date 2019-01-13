<?php
require_once "Tenderfoot/Lib/BaseModel.php";
class Model extends BaseModel
{
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
        $property = $this->$propertyName;
        if ($required && !_::HasValue($property))
        {
            $this->InvalidFields[] = $propertyName;
            return GetMessage("RequiredField", $propertyName);
        }
        if ($type == Type::Image)
        {
            $constants = new Image();
            $constants = new ReflectionClass(get_class($constants));
            $constants = array_values($constants->getConstants());
            if (!in_array($property["type"], $constants))
            {
                $this->InvalidFields[] = $propertyName;
                return GetMessage("InvalidFileType", $propertyName);
            }
        }
        else
        {
            if ($property != null)
            {
                if ($type != Type::All && !preg_match($type, $property)) 
                {
                    $this->InvalidFields[] = $propertyName;
                    return GetMessage("InvalidFieldInput", $propertyName);
                }
            }
            $validateLength = 
                is_string($property) && 
                (strlen($property) > $length) && 
                ($length != 0);
            if ($validateLength)
            {
                $this->InvalidFields[] = $propertyName;
                return GetMessage("InvalidFieldLength", $propertyName);
            }
        }
        return "";
    }
    function IsValid(string ...$propertyNames) : bool
    {
        foreach ($propertyNames as $propertyName)
        {
            if (in_array($propertyName, $this->InvalidFields))
            {
                return false;
            }
        }
        return true;
    }
    private $Controller;
    private $ViewName;
    function InitiatePage($controller, string $viewName) : void
    {
        $this->Controller = $controller;
        $this->ViewName = $viewName;
    }
    function RenderPage() : string
    {
        $view = new View($this->Controller, $this, $this->ViewName);
        if ($view->NotFound)
        {
            //Fix notfound page here.
        }
        else
        {
            return $view->View;
        }
        return "";
    }
    function Partial(string $partialView) : string
    {
        $view = new View($this->Controller, $this, $partialView);
        if ($view->NotFound)
        {
            //Fix notfound page here.
        }
        else
        {
            return $view->View;
        }
        return "";
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
        if (!file_exists($filePath))
        {
            mkdir($filePath);
        }
        file_put_contents($filePath."/".$fileName.$fileExtension, $file);
        return $urlPath."/".$fileName.$fileExtension;
    }
    function SaveFile(string $fileUrl, string $fileName = null) : string
    {
        $fileName = str_replace(" ", "_", $fileName);
        $urlPath = Settings::SiteUrl().Settings::FilePath();
        $newFilePath = $_SERVER['DOCUMENT_ROOT'].parse_url($urlPath, PHP_URL_PATH);
        $oldFilePath = $_SERVER['DOCUMENT_ROOT'].parse_url($fileUrl, PHP_URL_PATH);
        $fileExtension = pathinfo($oldFilePath, PATHINFO_EXTENSION);
        if ($fileName == null)
        {
            $fileName = pathinfo($oldFilePath, PATHINFO_FILENAME);
        }
        $newFilePath = $newFilePath."/".$fileName.".".$fileExtension;
        $directory = dirname($newFilePath);
        if (!file_exists($directory))
        {
            mkdir($directory);
        }
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
    const AlphaNumeric = "/^[a-zA-Z0-9 ]*$/";
    const Currency = "/^\d*\.?\d*$/";
    const Email = "/^([a-zA-Z0-9_+\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/";
    const Numeric = "/^-?[0-9]\d*(\.\d+)?$/";
    const Url = "/[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/";
    const Image = "Image";
    const PhoneNumber = "/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/";
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