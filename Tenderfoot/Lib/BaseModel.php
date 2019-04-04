<?php 
class BaseModel 
{
    public $IsValid = true;
    public $Messages = [];
    public $Uri = "";
    public $Environment = "";
    public $Deployment = "";
    public $SessionName = "";
    public $InvalidFields = array();
    public function __construct()
    {
        $this->BindModel();
        $this->MetaKeywords = Settings::MetaKeywords();
        $this->MetaDescription = Settings::MetaDescription();
    }
    private function BindModel() : void
    {
        foreach ($_REQUEST as $key => $value)
        {
            $this->SetValue($key, $value);
        }
        foreach ($_FILES as $key => $value)
        {
            $this->SetValue($key, $value);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        if ($json != null)
        {
            foreach ($json as $key => $value)
            {
                $this->SetValue($key, $value);
            }
        }   
    }
    private $SkipProperties = [
        "IsValid", 
        "Messages", 
        "Uri", 
        "Environment", 
        "Deployment", 
        "SessionName", 
        "InvalidFields"];
    private function SetValue(string $key, $value)
    {
        $fieldName = ucfirst($key);
        if (!in_array($fieldName, $this->SkipProperties))
        {
            if (property_exists($this, $fieldName))
            {
                $property = new ReflectionProperty($this, $fieldName);
                if ($property->isPublic())
                {
                    $this->$fieldName = $value;   
                }
            }
        }
    }
    public $MetaKeywords = "";
    public $MetaDiscription = "";
    function AddMetaKeywords(string ...$keywords) : void
    {
        $this->metaKeywords = "$this->MetaKeywords, ".join(", ", $keywords);
    }
    function AddMetaDescription(string $description) : void
    {
        if (Obj::HasValue($description))
        {
            $this->metaDiscription = Chars::Clip($description, 300);
        }
    }
}
?>