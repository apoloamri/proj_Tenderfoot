<?php 
class BaseModel 
{
    public function __construct()
    {
        $this->BindModel();
    }

    private function BindModel()
    {
        $reflect = new ReflectionClass($this);
        foreach ($_REQUEST as $key => $value)
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
}
?>