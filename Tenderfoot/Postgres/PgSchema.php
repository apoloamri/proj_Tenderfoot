<?php 
require_once "Tenderfoot/Lib/BasePgSchema.php";
class Schema extends BasePgSchema
{
    function __construct(string $tableName, bool $createTable = true)
    {
        $reflect = new ReflectionClass($this);
        $this->InitializeConnection();
        $this->Columns = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $this->TableName = $tableName;
        if (Settings::Migrate() && $createTable)
        {
            $this->CreateTable();
            $this->UpdateColumns();
        }
    }
    function Select(string ...$columns) : array
    {
        $columns = count($columns) > 0 ? join(", ", $columns) : "*";
        $where = $this->GetWhere(true);
        $order = $this->GetOrder();
        $limit = $this->GetLimit();
        $query = "SELECT $columns FROM $this->TableName $this->Join $where $order $limit;";
        $return = array();
        $result = $this->Execute($query);
        while ($data = pg_fetch_assoc($result))
        {
            $model = (object)$data;
            $return[] = $model;
        }
        return array_filter($return);
    }
    function SelectSingle(string ...$columns) : object
    {
        $result = $this->Select(...$columns);
        if (count($result) == 1)
        {
            ModelOverwrite($this, $result[0]);
            return $result[0];
        }
        return array();
    }
    function Count(string ...$columns) : int
    {
        $columns = count($columns) > 0 ? join(", ", $columns) : "*";
        $where = $this->GetWhere(true);
        $query = "SELECT COUNT($columns) FROM $this->TableName $this->Join $where;";
        $result = $this->Execute($query);
        return intval(pg_fetch_assoc($result)["count"]);
    }
    function Join($schema, string $column)
    {
        $this->Join = "INNER JOIN $schema->TableName ON $this->TableName.$column = $schema->TableName.$column";
    }
    function Where(string $column, string $expression, $value, string $condition = DB::AND) : void
    {
        $this->Where[] = "$this->TableName.$column $expression ".$this->PgEscapeLiteral($value)." $condition";
    }
    function OrderBy(string $column, string $order = DB::ASC) : void
    {
        $this->Order[] = "$this->TableName.$column $order";
    }
    function Limit(int $limit) : void
    {
        $this->Limit = $limit;
    }
    function Clear() : void
    {
        $this->Where = array();
        $this->Orders = array();
        $this->Limit = null;
    }
    function Insert() : void
    {
        $columns = array();
        $values = array();
        foreach ($this->Columns as $column)
        {
            $value = $column->getValue($this);
            if ($value != null)
            {
                $columns[] = $column->getName();
                $values[] = $this->PgEscapeLiteral($value);
            }
        }
        if (count($columns) > 0)
        {
            $query = "INSERT INTO $this->TableName(".join(", ", $columns).") VALUES(".join(", ", $values).");";
            $this->Execute($query);
            $query = "SELECT currval('".$this->TableName."_id_seq')";
            $result = $this->Execute($query, false);
            $this->id = pg_fetch_assoc($result)["currval"];
        }
    }
    function Update() : void
    {
        $updateValues = array();
        foreach ($this->Columns as $column)
        {
            $value = $column->getValue($this);
            if ($value != null)
            {
                $updateValues[] = $column->getName()." = ".$this->PgEscapeLiteral($value);
            }
        }
        if (count($updateValues) > 0)
        {
            $where = $this->GetWhere();
            $query = "UPDATE $this->TableName SET ".join(", ", $updateValues)." $where;";
            $this->Execute($query);
        }
    }
    function Delete() : void
    {
        $where = $this->GetWhere(true);
        $query = "DELETE FROM $this->TableName $where;";
        $this->Execute($query);
    }
    function OverwriteWithModel($model) : void
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
    private function CreateTable() : void
    {
        $table = new InformationSchemaTables();
        $table->table_name = $this->TableName;
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
    private function UpdateColumns() : void
    {
        $alterColumns = array();
        foreach ($this->Columns as $property)
        {
            $columnName = $property->getName();
            $table = new InformationSchemaColumns();
            $table->table_name = $this->TableName;
            $table->column_name = $columnName;
            if ($table->Count("column_name") == 0)
            {
                $column = $this->GetColumnType($property);
                $alterColumns[] = "ADD COLUMN $column;";
            }
        }
        if (count($alterColumns) > 0)
        {
            $alterColumns = join(" ", $alterColumns);
            $query = "ALTER TABLE $this->TableName $alterColumns";
            $this->Execute($query);
        }
    }
}
class InformationSchemaTables extends Schema
{
    function __construct()
    {
        parent::__construct("information_schema.tables", false);
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
class InformationSchemaColumns extends Schema
{
    function __construct()
    {
        parent::__construct("information_schema.columns", false);
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
        parent::__construct("sessions");
    }
    public $str_session_id;
    public $str_session_key;
    public $dat_session_time;
    function GetSession() : string
    {
        if ($this->CheckSession())
        {
            return $this->str_session_id;
        }
        $sessionString = GenerateRandomString(50);
        $this->str_session_id = $sessionString;
        while ($this->Count() != 0)
        {
            $this->Clear();
            $this->str_session_id = $sessionString;
        }
        $this->dat_session_time = Now();
        $this->Insert();
        return $this->str_session_id;
    }
    function CheckSession() : bool 
    {
        if (!HasValue($this->str_session_id))
        {
            return false;
        }
        $this->Where("dat_session_time", DB::GreaterThan, Now(-Settings::Session()));
        if ($this->Count() == 0)
        {
            return false;
        }
        $this->Clear();
        $this->dat_session_time = Now();
        $this->Where("str_session_id", DB::Equal, $this->str_session_id);
        $this->Update();
        return true;
    }
    function ValidateSession() : string
    {
        if (!$this->CheckSession())
        {
            return GetMessage("InvalidAccess");
        }
        return "";
    }
}
class Accesses extends Schema
{
    function __construct()
    {
        parent::__construct("accesses");
    }
    public $str_key;
    public $str_password;
    function ValidateAccess() : string
    {
        if ($this->Count() == 0)
        {
            return GetMessage("InvalidAccess");
        }
        return "";
    }
}
?>