<?php
class Email
{
    private $EmailView;
    private $Model;
    private $EmailTo = array();
    private $EmailCc = array();
    function __construct($model, string $emailView)
    {
        $this->Model = $model;
        $this->EmailView = $emailView;
    }
    function AddEmailTo(string ...$emailTo) : void
    {
        $this->EmailTo = $emailTo;
    }
    function AddEmailCc(string ...$EmailCc) : void
    {
        $this->EmailCc = $emailCc;
    }
    function SendEmail() : void
    {
        $emailFrom = Settings::Email();
        $this->EmailTo[] = Settings::EmailAdmin();
        $headers = 
            "MIME-Version: 1.0 \r\n".
            "Content-type:text/html;charset=UTF-8 \r\n".
            "From: $emailFrom \r\n";
        if (count($this->EmailCc) > 0)
        {
            $emailCc = join(", ", $this->EmailCc);
            $headers .=
                "Cc: $emailCc";
        }
        $emailTo = join(", ", $this->EmailTo);
        $model = $this->Model;
        $message = $this->CompileView();
        $subject = $this->GetTitle($message);
        mail($emailTo, $subject, $message, $headers);
    }
    private function GetTitle(string $message) : string
    {
        $result = preg_match("/<title>(.*)<\/title>/siU", $message, $titleMatch);
        if (!$result) 
        {
            return "";
        }
        $title = preg_replace('/\s+/', ' ', $titleMatch[1]);
        $title = trim($title);
        return $title;
    }
    private function CompileView() : string
    {
        $view = file_get_contents("Emails/$this->EmailView.html");
        $modelReflect = new ReflectionClass($this->Model);
        foreach ($modelReflect->getProperties(ReflectionProperty::IS_PUBLIC) as $property)
        {
            $name = $property->getName();
            if (StringContains("[$name]", $view))
            {
                $newValue = (string)$this->Model->$name;
                $view = str_replace("[$name]", "$newValue", $view);
            }
        }
        return $view;
    }
}
?>