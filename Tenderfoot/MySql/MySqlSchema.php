<?php 
require_once "Tenderfoot/Lib/BaseMySqlSchema.php";
class MySqlSchema extends BaseMySqlSchema
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
    /**
     * Executes SELECT statement. 
     ** Populate $columns to select particular columns only.
     */
    function Select(string ...$columns) : array
    {
        $compiledColumns = count($columns) > 0 ? join(", ", $columns) : "$this->TableName.*";
        if (count($columns) == 0 && count($this->JoinSchema) > 0)
        {
            foreach ($this->JoinSchema as $schema)
            {
                $joinColumns = array();
                foreach ($schema->Columns as $column)
                {
                    $columnName = $column->getName();
                    $joinColumns[] = 
                        $schema->TableName.".".$columnName.
                        ($columnName == "id" ? " AS '$schema->TableName-$columnName'" : "");
                }
                $compiledColumns = $compiledColumns.", ".join(", ", $joinColumns);
            }
        }
        $join = $this->GetJoin();
        $where = $this->GetWhere(true);
        $order = $this->GetOrder();
        $group = $this->GetGroup();
        $limit = $this->GetLimit();
        $query = "SELECT $compiledColumns FROM $this->TableName $join $where $group $order $limit;";
        $return = array();
        $result = $this->Execute($query);
        while ($data = mysqli_fetch_assoc($result))
        {
            $model = (object)$data;
            $return[] = $model;
        }
        return array_filter($return);
    }
    /**
     * Executes SELECT statement with column DISTINCT. 
     ** Populate $columns to select particular columns only.
     */
    function SelectDistinct(string ...$columns) : array
    {
        return $this->Select("DISTINCT ".join(", ", $columns));
    }
    /**
     * Executes SELECT statement with only one result. Automatically populates the entity.
     ** Populate $columns to select particular columns only.
     */
    function SelectSingle(string ...$columns) : object
    {
        $result = $this->Select(...$columns);
        if (count($result) == 1)
        {
            ModelOverwrite($this, $result[0]);
            return $result[0];
        }
        return new stdClass();
    }
    /**
     * Counts the number of records of the current criteria.
     ** Populate $columns to select particular columns only.
     */
    function Count(string ...$columns) : int
    {
        $columns = count($columns) > 0 ? join(", ", $columns) : "*";
        $join = $this->GetJoin();
        $where = $this->GetWhere(true);
        $group = $this->GetGroup();
        $query = "SELECT COUNT($columns) as count FROM $this->TableName $join $where $group;";
        $result = $this->Execute($query);
        return intval(mysqli_fetch_assoc($result)["count"]);
    }
    /**
     * Checks if records exists with the current criteria.
     ** Populate $columns to select particular columns only.
     */
    function Exists(string ...$columns) : bool
    {
        return $this->Count(...$columns) > 0;
    }
    /**
     * Joins a new table / schema through LEFT JOIN.
     ** $schema - Table to be joined.
     ** $joinColumn - Column of the joint table used for comparison.
     ** $parentColumn - Column of the current schema used for comparison with $joinColumn.
     */
    function Join($schema, string $joinColumn, string $parentColumn)
    {
        $parentColumn = 
            StringContains("-", $parentColumn) ? "'$parentColumn'" : 
            StringContains(".", $parentColumn) ? "$parentColumn" : 
            "$this->TableName.$parentColumn";
        $this->Join[] = "LEFT JOIN $schema->TableName ON $parentColumn = $schema->TableName.$joinColumn";
        $this->JoinSchema[] = $schema;
    }
    /**
     * Creates / adds new criterias for the current SELECT statement.
     ** $column - Column name of the table to be used for the criteria.
     ** $expression - Condition of the criteria. Use DB const.
     ** $value - Value used for comparison.
     ** $condition - Condition for the next criteria.
     */
    function Where(string $column, string $expression = DB::Equal, $value = null, string $condition = DB::AND) : void
    {
        $this->Where[] = "$column $expression '".$this->MySqliEscapeLiteral($value)."' $condition";
    }
    function Combine(string $condition = DB::AND)
    {
        $where = "(".str_replace("WHERE", "", $this->GetWhere(true, false)).") $condition";
        $this->Where = array();
        $this->Wheres[] = $where;
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
            $name = $column->getName();
            $value = $column->getValue($this);
            if ($name != "id" && !is_null($value))
            {
                $columns[] = $name;
                $values[] = "'".$this->MySqliEscapeLiteral($value)."'";
            }
        }
        if (count($columns) > 0)
        {
            $query = "INSERT INTO $this->TableName(".join(", ", $columns).") VALUES(".join(", ", $values).");";
            $this->Execute($query);
            $this->SelectSingle("id");
        }
    }
    function Update() : void
    {
        $updateValues = array();
        foreach ($this->Columns as $column)
        {
            $value = $column->getValue($this);
            if (!is_null($value))
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
class InformationSchemaTables extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("INFORMATION_SCHEMA.TABLES", false);
    }
    public $SCHEMA_NAME;
    public $TABLE_NAME;
    public $COLUMN_NAME;
}
class InformationSchemaColumns extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("INFORMATION_SCHEMA.COLUMNS", false);
    }
    public $SCHEMA_NAME;
    public $TABLE_NAME;
    public $COLUMN_NAME;
}
class Sessions extends MySqlSchema
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
        $this->str_session_id = GenerateRandomString(50);
        while ($this->Count() != 0)
        {
            $this->Clear();
            $this->str_session_id = GenerateRandomString(50);
        }
        $this->dat_session_time = Now();
        $this->Insert();
        return $this->str_session_id;
    }
    /**
     * Populate $sessionKey to add / update a new key for the current session.
     */
    function CheckSession(string $sessionKey = "") : bool 
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
        if (HasValue($sessionKey))
        {
            $this->str_session_key = $sessionKey;
        }
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
class Accesses extends MySqlSchema
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