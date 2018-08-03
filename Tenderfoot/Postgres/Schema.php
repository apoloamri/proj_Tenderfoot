<?php 
require_once "Tenderfoot/Lib/BaseSchema.php";
class Schema extends BaseSchema
{
    function __construct(string $tableName, $createTable = false)
    {
        $reflect = new ReflectionClass($this);
        $this->Columns = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $this->TableName = $tableName;
        if ($createTable)
        {
            $this->CreateTable();
        }
    }
    function Select(string ...$columns)
    {
        $columns = count($columns) > 0 ? join(", ", $columns) : "*";
        $where = $this->GetWhere();
        $query = "SELECT $columns FROM $this->TableName $where;";
        $return = array();
        $result = $this->Execute($query);
        while ($data = pg_fetch_assoc($result))
        {
            $return[] = $data;
        }
        return array_filter($return);
    }
    function Count(string ...$columns)
    {
        $columns = count($columns) > 0 ? join(", ", $columns) : "*";
        $where = $this->GetWhere();
        $query = "SELECT COUNT($columns) FROM $this->Tablename $where;";
        $result = $this->Execute($query);
        return intval(pg_fetch_assoc($result)["count"]);
    }
    function AddWhere(string $column, $value, string $expression = DB::Equal, string $condition = DB::AND)
    {
        $count = count($this->Parameters) + 1;
        $this->AddParameterValue($value, "$column $expression %s $condition");
    }
    function Insert()
    {
        $columns = array();
        foreach ($this->Columns as $column)
        {
            $value = $column->getValue($this);
            if ($value != null)
            {
                $this->AddParameterValue($value, "%s");
                $columns[] = $column->getName();
            }
        }
        if (count($columns) != 0)
        {
            $query = "INSERT INTO $this->TableName(".join(", ", $columns).") VALUES(".join(", ", $this->Parameters).");";
            $this->Execute($query);
            $query = "SELECT currval('".$this->TableName."_id_seq')";
            $result = $this->Execute($query, false);
            $this->id = pg_fetch_assoc($result)["currval"];
        }
    }
    function Update()
    {
        $isUpdate = false;
        foreach ($this->Columns as $column)
        {
            $value = $column->getValue($this);
            if ($value != null)
            {
                $this->AddParameterValue($value, "$column->getName() = %s");
                $isUpdate = true;
            }
        }
        if ($isUpdate)
        {
            $where = $this->GetWhere();
            $query = "UPDATE $this->TableName SET ".join(", ", $this->Parameters)." $where;";
            $this->Execute($query);
        }
    }
    function Delete()
    {
        $where = $this->GetWhere();
        $query = "DELETE FROM $this->TableName $where;";
        $this->Execute($query);
    }
    function OverwriteWithModel($model)
    {
        $modelReflect = new ReflectionClass($this);
        $valuesReflect = new ReflectionClass($model);
        foreach ($modelReflect->getProperties(ReflectionProperty::IS_PUBLIC) as $property)
        {
            $name = $property->getName();
            $value = null;
            if (property_exists($model, $name))
            {
                $value = $valuesReflect
                    ->getProperty($name)
                    ->getValue($model);
            }
            else if (property_exists($model, $name = substr($name, 4)))
            {
                $value = $valuesReflect
                    ->getProperty($name)
                    ->getValue($model);
            }
            if ($value != null)
            {
                $property->setValue($this, $value);
            }
        }
    }
    private function CreateTable()
    {
        $table = new InformationSchemaTables();
        $table->AddWhere("table_name", $this->TableName);
        if ($table->Count("table_name") == 0)
        {
            $columns;
            foreach ($this->Columns as $property)
            {
                $columns[] = $this->GetColumnType($property);
            }
            $columns = join(", ", $columns);
            $query = "CREATE TABLE $this->TableName ($columns);";
            $this->Execute($query);
        }
    }
}
class InformationSchemaTables extends Schema
{
    function __construct()
    {
        parent::__construct("sessions");
    }
    public $table_catalog;
    public $table_schema;
    public $table_name;
    public $table_type;
    public $column_name;
    public $ordinal_position;
    public $column_default;
    public $is_nullable;
    public $data_type;
    public $character_maximum_length;
}
class Sessions extends Schema
{
    function __construct()
    {
        parent::__construct("sessions", true);
    }
    public $str_session_id;
    public $str_session_key;
    public $dat_session_time;
}
?>