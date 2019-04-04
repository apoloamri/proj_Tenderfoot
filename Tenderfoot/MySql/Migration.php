<?php
class Migration
{
    function __construct()
    {
        $this->Migrate("Sessions");
    }
    function Migrate(string $schemaName)
    {
        TempData::Set("migrate", true);
        $path = "Schemas/$schemaName.php";
        if (file_exists($path))
        {
            require_once $path;
            $schema = new $schemaName();
            echo "<b>$schemaName table</b> ==into==> <b>database: ".Settings::Database()."</b> migration completed -- ".Date::Now()."<br/>";
        }
        else if ($schemaName == "Sessions" || $schemaName == "Accesses")
        {
            $schema = new $schemaName();
            echo "DEFAULT <b>$schemaName table</b> ==into==> <b>database: ".Settings::Database()."</b> migration completed -- ".Date::Now()."<br/>";
        }
    }
    function Seed(string $schemaName, $object)
    {
        $path = "Schemas/$schemaName.php";
        if (file_exists($path))
        {
            require_once $path;
            $schema = new $schemaName();
            $schema->Where("id", DB::Equal, $object->id);
            if ($schema->Exists())
            {
                Obj::Overwrite($schema, $object);
                $schema->Update();
            }
            else
            {
                Obj::Overwrite($schema, $object);
                $schema->Insert();
            }
            echo "Seeded an item into <b>$schemaName table</b> -- ".Date::Now()."<br/>";
        }
    }
}
?>