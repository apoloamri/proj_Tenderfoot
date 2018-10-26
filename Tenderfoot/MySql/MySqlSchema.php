<?php 
require_once "Tenderfoot/Lib/BaseMySqlSchema.php";
class Schema extends BaseMySqlSchema
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
        $columns = count($columns) > 0 ? join(", ", $columns) : "$this->TableName.*";
        if (count($this->JoinSchema) > 0)
        {
            foreach ($this->JoinSchema as $schema)
            {
                $joinColumns = array();
                foreach ($schema->Columns as $column)
                {
                    if ($column->getName() != "id")
                    {
                        $joinColumns[] = $schema->TableName.".".$column->getName();
                    }
                }
                $columns = $columns.", ".join(", ", $joinColumns);
            }
        }
        $where = $this->GetWhere(true);
        $join = $this->GetJoin();
        $order = $this->GetOrder();
        $group = $this->GetGroup();
        $limit = $this->GetLimit();
        $query = "SELECT $columns FROM $this->TableName $join $where $group $order $limit;";
        $return = array();
        $result = $this->Execute($query);
        while ($data = mysqli_fetch_assoc($result))
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
        $group = $this->GetGroup();
        $query = "SELECT COUNT($columns) as count FROM $this->TableName $where $group;";
        $result = $this->Execute($query);
        return intval(mysqli_fetch_assoc($result)["count"]);
    }
    function Exists(string ...$columns) : bool
    {
        return $this->Count(...$columns) > 0;
    }
    function Join($schema, string $joinColumn, string $parentColumn)
    {
        $this->Join[] = "LEFT JOIN $schema->TableName ON $this->TableName.$parentColumn = $schema->TableName.$joinColumn";
        $this->JoinSchema[] = $schema;
    }
    function Where(string $column, string $expression, $value, string $condition = DB::AND) : void
    {
        $this->Where[] = "$this->TableName.$column $expression '".$this->MySqliEscapeLiteral($value)."' $condition";
    }
    function GroupBy(string $group)
    {
        $this->Group[] = "$this->TableName.$group";
    }
    function OrderBy(string $column, string $order = DB::ASC) : void
    {
        $this->Order[] = "$this->TableName.$column $order";
    }
    function Limit(int $limit) : void
    {
        $this->Limit = $limit;
    }
    function Page(int $page, int $itemCount) : void
    {
        $page = ($page - 1) * $itemCount;
        $this->Page = $page.", ".$itemCount;
    }
    function PageCount(int $itemCount) : int
    {
        return ceil($this->Count() / $itemCount);
    }
    function Clear() : void
    {
        $this->Where = array();
        $this->Join = array();
        $this->JoinSchema = array();
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
                $values[] = "'".$this->MySqliEscapeLiteral($value)."'";
            }
        }
        if (count($columns) > 0)
        {
            $query = "INSERT INTO $this->TableName(".join(", ", $columns).") VALUES(".join(", ", $values).");";
            $this->Execute($query);
            $query = "SELECT id FROM $this->TableName ORDER BY id DESC LIMIT 1;";
            $result = $this->Execute($query, false);
            $this->id = mysqli_fetch_assoc($result)["id"];
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
                $updateValues[] = $column->getName()." = '".$this->MySqliEscapeLiteral($value)."'";
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
        $table->TABLE_NAME = $this->TableName;
        if ($table->Count("TABLE_NAME") == 0)
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
            $table->TABLE_NAME = $this->TableName;
            $table->COLUMN_NAME = $columnName;
            if ($table->Count("COLUMN_NAME") == 0)
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
        parent::__construct("INFORMATION_SCHEMA.TABLES", false);
    }
    public $SCHEMA_NAME;
    public $TABLE_NAME;
    public $COLUMN_NAME;
}
class InformationSchemaColumns extends Schema
{
    function __construct()
    {
        parent::__construct("INFORMATION_SCHEMA.COLUMNS", false);
    }
    public $SCHEMA_NAME;
    public $TABLE_NAME;
    public $COLUMN_NAME;
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