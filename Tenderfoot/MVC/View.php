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
            file_exists("Views/$controllerName/$this->ViewFile.html") ? 
            file_get_contents("Views/$controllerName/$this->ViewFile.html") :
            "";
        if (!_::HasValue($this->View))
        {
            $this->View = 
                file_exists("Emails/$this->ViewFile.html") ?
                file_get_contents("Emails/$this->ViewFile.html") :
                "";
        }
        if (_::HasValue($this->View))
        {
            $this->View = $this->GetValues($this->Model);
        }
    }
    private function GetValues($model, string $modelName = "", string $view = "") : string
    {
        $subView = _::HasValue($view);
        $view = _::HasValue($view) ? $view : $this->View;
        foreach ($model as $name => $value)
        {
            if (!_::HasValue($model->$name))
            {
                continue;
            }
            $property = (_::HasValue($modelName) ? "$modelName." : "").$name;
            $pattern = "/\<$property\.(.*)\/>/siU";
            if (_::StringContains("<$property/>", $view))
            {
                $view = str_replace("<$property/>", "$value", $view);
            }
            else if (_::StringContains("<if.$property>", $view))
            {
                $pattern = "/<if.$property>([\s\S]*)<\/if.$property>/siU";
                $result = preg_match($pattern, $view, $ifMatch);
                if (!$result)
                {
                    continue;
                }
                if ($model->$name)
                {
                    if (_::StringContains("<else.$property/>", $ifMatch[1]))
                    {
                        $result = preg_match("/<if.$property>([\s\S]*)<else.$property\/>/siU", $view, $ifInnerMatch);
                        if (!$result)
                        {
                            continue;
                        }
                        $view = preg_replace($pattern, $ifInnerMatch[1], $view);
                    }
                    else
                    {
                        $view = preg_replace($pattern, $ifMatch[1], $view);
                    }
                }
                else if (_::StringContains("<else.$property/>", $ifMatch[1]))
                {
                    $result = preg_match("/<else.$property>([\s\S]*)<\/if.$property>/siU", $view, $elseMatch);
                    $view = preg_replace($pattern, $elseMatch[1], $view);
                }
            }
            else if (_::StringContains("<$property>", $view))
            {
                $pattern = "/<$property>([\s\S]*)<\/$property>/siU";
                $result = preg_match($pattern, $view, $objMatch);
                if (!$result)
                {
                    continue;
                }
                $subView = $this->GetValues($model->$name, $property, $objMatch[1]);
                $view = preg_replace($pattern, $subView, $view);
            }
            else if (_::StringContains("<foreach.$property>", $view))
            {
                $pattern = "/<foreach.$property>([\s\S]*)<\/foreach.$property>/siU";
                $result = preg_match($pattern, $view, $foreachMatch);
                if (!$result)
                {
                    continue;
                }
                $subViews = array();
                foreach ($model->$name as $subModel)
                {
                    $subViews[] = $this->GetValues($subModel, $name, $foreachMatch[1]);
                }
                $view = preg_replace($pattern, join(" ", $subViews), $view);
            }
        }
        if ($subView)
        {
            return $view;
        }
        else
        {
            $this->View = $view;
            return "";
        }
    }
    private function GetTitle() : void
    {
        $result = preg_match("/<title>(.*)<\/title>/siU", $this->View, $titleMatch);
        if (!$result) 
        {
            return;
        }
        $title = preg_replace('/\s+/', ' ', $titleMatch[1]);
        $this->Title = trim($title);
    }
}
?>