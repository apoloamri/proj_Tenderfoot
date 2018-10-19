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
function HasValue($value) : bool
{
    if (!isset($value))
    {
        return false;
    }
    else if (strlen($value) == 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}
function Now(int $minutes = 0) : string
{
    $minutes = $minutes * 60;
    return date("Y/m/d H:i:s", time() + $minutes);
}
function ModelOverwrite($model, $values) : void
{
    $modelReflect = new ReflectionClass($model);
    foreach ($modelReflect->getProperties(ReflectionProperty::IS_PUBLIC) as $property)
    {
        $name = $property->getName();
        if (array_key_exists($name, $values))
        {
            $property->setValue($model, $values->$name);
        }
    }
}
function ArrayOverwrite($model, $values) : void
{
    $modelReflect = new ReflectionClass($model);
    foreach ($modelReflect->getProperties(ReflectionProperty::IS_PUBLIC) as $property)
    {
        $name = $property->getName();
        if (array_key_exists($name, $values))
        {
            $property->setValue($model, $values[$name]);
        }
    }
}
?>