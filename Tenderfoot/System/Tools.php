<?php
function IsNullOrEmpty($value)
{
    if (strlen($value) == 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}
function Now()
{
    return date("Y/m/d H:i:s");
}
function OverwriteModel($model, $values)
{
    $modelReflect = new ReflectionClass($model);
    $valuesReflect = new ReflectionClass($values);
    foreach ($valuesReflect->getProperties(ReflectionProperty::IS_PUBLIC) as $property)
    {
        $name = $property->getName();
        if (property_exists($model, $name))
        {
            $modelReflect
                ->getProperty($name)
                ->setValue($model, $property->getValue($values));
        }
    }
}
?>