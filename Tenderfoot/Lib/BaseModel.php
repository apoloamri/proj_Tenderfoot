<?php 
class BaseModel 
{
    public $IsValid = true;
    public $Messages = null;
    public $URI = null;
    public $Environment = "";
    public $Deployment = "";
    public $InvalidFields = array();
    public function __construct()
    {
        $this->BindModel();
        $this->MetaKeywords = Settings::MetaKeywords();
        $this->MetaDescription = Settings::MetaDescription();
    }
    private function BindModel() : void
    {
        $reflect = new ReflectionClass($this);
        foreach ($_REQUEST as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $reflect->getProperty($key)->setValue($this, $value);
            }
        }
        foreach ($_FILES as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $reflect->getProperty($key)->setValue($this, $value);
            }
        }
        $json = json_decode(file_get_contents('php://input'), true);
        if ($json != null)
        {
            foreach ($json as $key => $value)
            {
                if (property_exists($this, $key))
                {
                    $reflect->getProperty($key)->setValue($this, $value);
                }
            }
        }   
    }
    public $MetaKeywords = "";
    public $MetaDescription = "";
    function AddMetaKeywords(string ...$keywords) : void
    {
        $this->MetaKeywords = "$this->MetaKeywords, ".join(", ", $keywords);
    }
    function AddMetaDescription(string $description) : void
    {
        if (Obj::HasValue($description))
        {
            $this->MetaDescription = Chars::Clip($description, 300);
        }
    }
}
?>