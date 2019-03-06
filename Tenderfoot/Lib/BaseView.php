<?php
class BaseView
{
    public $NotFound = false;
    public $Title = "";
    public $View = "";
    protected $Controller;
    protected $Model;
    protected $ViewFile;
    protected function GetValues($model, string $modelName = "", string $view = "") : string
    {
        $subView = Obj::HasValue($view);
        $view = Obj::HasValue($view) ? $view : $this->View;
        foreach ($model as $name => $value)
        {
            $property = (Obj::HasValue($modelName) ? "$modelName." : "").$name;
            if (Chars::Contains("<$property/>", $view))
            {
                $view = str_replace("<$property/>", "$value", $view);
            }
            $ifResult = preg_match_all("/<if\.$property([\s\S]*?)>([\s\S]*?)<\/if\.$property>/", $view, $ifMatch);
            if ($ifResult)
            {
                foreach ($ifMatch[0] as $match)
                {
                    $equals = $this->GetEqualsValue($match);
                    $notEquals = $this->GetNotEqualsValue($match);
                    if (is_bool($model->$name) && $model->$name)
                    {
                        $value = $this->GetIfValue($view, $property, $match);
                        if (Obj::HasValue($value))
                        {
                            $view = $value;
                        }
                        continue;
                    }
                    else if (
                        !is_bool($model->$name) && 
                        Obj::HasValue($equals) && 
                        $model->$name == $equals)
                    {
                        $value = $this->GetIfValue($view, $property, $match);
                        if (Obj::HasValue($value))
                        {
                            $view = $value;
                        }
                        continue;
                    }
                    else if (
                        !is_bool($model->$name) && 
                        Obj::HasValue($notEquals) && 
                        $model->$name != $notEquals)
                    {
                        $value = $this->GetIfValue($view, $property, $match);
                        if (Obj::HasValue($value))
                        {
                            $view = $value;
                        }
                        continue;
                    }
                    else if (Chars::Contains("<else.$property/>", $match))
                    {
                        $result = preg_match_all("/<else\.$property\/>([\s\S]*?)<\/if\.$property>/", $match, $elseMatch);
                        if (!$result)
                        {
                            continue;
                        }
                        $view = str_replace($match, $elseMatch[1][0], $view);
                    }
                }
            }
            $objResult = preg_match_all("/<$property>([\s\S]*?)<\/$property>/", $view, $objMatch);
            if ($objResult)
            {
                foreach ($objMatch[0] as $match)
                {
                    $objView = $this->GetValues($model->$name, $property, $match);
                    $view = str_replace($match, Chars::Between($objView, "<$property>", "</$property>"), $view);
                }
            }
            $foreachResult = preg_match_all("/<foreach\.$property>([\s\S]*?)<\/foreach\.$property>/", $view, $foreachMatch);
            if ($foreachResult)
            {
                foreach ($foreachMatch[1] as $index => $match)
                {
                    $subViews = array();
                    foreach ($model->$name as $subModel)
                    {
                        if (is_object($subModel))
                        {
                            $subViews[] = $this->GetValues($subModel, $name, $match);
                        }
                        else
                        {
                            $subViews[] = str_replace("<$property.$name/>", $subModel, $match);
                        }
                    }
                    $view = str_replace($foreachMatch[0][$index], join(" ", $subViews), $view);   
                }
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
    protected function GetRenders() : void
    {
        if (Chars::Contains("<renderPage/>", $this->View))
        {
            $this->View = str_replace("<renderPage/>", $this->Model->RenderPage(), $this->View);
        }
        else
        {
            $pattern = "/<partial\..([\s\S]*?)\/>/";
            $result = preg_match_all($pattern, $this->View, $renderMatch);
            if ($result)
            {
                foreach ($renderMatch[0] as $match)
                {
                    $partial = Chars::Between($match, ".", "/");
                    $this->View = str_replace($match, $this->Model->Partial($partial), $this->View);
                }
            }
        }
    }
    protected function GetTitle() : void
    {
        $result = preg_match("/<title>(.*)<\/title>/siU", $this->View, $titleMatch);
        if (!$result) 
        {
            return;
        }
        $title = preg_replace('/\s+/', ' ', $titleMatch[1]);
        $this->Title = trim($title);
    }
    private function GetIfValue(string $view, string $property, string $match) : string
    {
        if (Chars::Contains("<else.$property/>", $match))
        {
            $result = preg_match_all("/<if\.$property([\s\S]*?)>([\s\S]*?)<else\.$property\/>/", $match, $ifInnerMatch);
            if (!$result)
            {
                return "";
            }
            return str_replace($match, $ifInnerMatch[count($ifInnerMatch) - 1][0], $view);
        }
        return str_replace($match, $match, $view);
    }
    private function GetEqualsValue(string $value) : string
    {
        $result = preg_match("/equals=\"([\s\S]*?)\"/", $value, $equalsMatch);
        if ($result)
        {
            return $equalsMatch[1];
        }
        return "";
    }
    private function GetNotEqualsValue(string $value) : string
    {
        $result = preg_match("/notEquals=\"([\s\S]*?)\"/", $value, $equalsMatch);
        if ($result)
        {
            return $equalsMatch[1];
        }
        return "";
    }
}
?>