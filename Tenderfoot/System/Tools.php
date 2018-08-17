<?php
function GenerateRandomString($length = 10) : string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) 
    {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function IsNullOrEmpty($value) : bool
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
function Now() : date
{
    return date("Y/m/d H:i:s");
}
function OverwriteModel($model, $values) : void
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