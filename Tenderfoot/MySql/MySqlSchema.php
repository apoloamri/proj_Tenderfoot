<?php 
require_once "Tenderfoot/Lib/BaseMySqlSchema.php";
class MySqlSchema extends BaseMySqlSchema
{
    function __construct(string $tableName)
    {
        $reflect = new ReflectionClass($this);
        $this->InitializeConnection();
        $this->Columns = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $this->TableName = $tableName;
        if (TempData::Get("migrate"))
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
        $query = $this->GetQuery($columns).";";
        $return = array();
        $result = $this->Execute($query);
        if ($result)
        {
            while ($data = mysqli_fetch_assoc($result))
            {
                $model = (object)$data;
                $return[] = $model;
            }
            return array_filter($return);
        }
        return array();
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
            Obj::Overwrite($this, $result[0]);
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
        if ($result)
        {
            return intval(mysqli_fetch_assoc($result)["count"]);
        }
        return 0;
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
     * Checks if a single record exists with the current criteria.
     ** Populate $columns to select particular columns only.
     */
    function ExistsOne(string ...$columns) : bool
    {
        return $this->Count(...$columns) == 1;
    }
    /**
     * Joins a new table / schema through LEFT JOIN.
     ** $schema - Table to be joined.
     ** $joinColumn - Column of the joint table used for comparison.
     ** $parentColumn - Column of the current schema used for comparison with $joinColumn.
     */
    function Join($schema, string $joinColumn, string $parentColumn) : void
    {
        if (Chars::Contains("->", $parentColumn))
        {
            $parentColumn = str_replace("->", ".", $parentColumn);
        }
        else
        {
            $parentColumn = 
                Chars::Contains("-", $parentColumn) ? 
                "'$parentColumn'" : 
                "$this->TableName.$parentColumn";
        }
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
        if (Chars::Contains("->", $column))
        {
            $this->Where[] = str_replace("->", ".", $column)." $expression '".$this->Sanitize($value)."' $condition";    
        }
        else
        {
            $this->Where[] = $this->TableName.".$column $expression '".$this->Sanitize($value)."' $condition";
        }
    }
    function In(string $column, array $values)
    {
        $this->Where[] = "$column IN (".join(",", "'".$this->Sanitize($value)."'");
    }
    function InQuery(string $column, $schema, string ...$columns)
    {
        $this->Where[] = "$column IN (".$schema->GetQuery($columns).")";
    }
    function Combine(string $condition = DB::AND)
    {
        $where = "(".str_replace("WHERE", "", $this->GetWhere(true, false)).") $condition";
        $this->Where = array();
        $this->Wheres[] = $where;
    }
    function GroupBy(string $group)
    {
        if (Chars::Contains("->", $group))
        {
            $this->Group[] = str_replace("->", ".", $column).$group;
        }
        else
        {
            $this->Group[] = "$this->TableName.$group";
        }
    }
    function OrderBy(string $column, string $order = DB::ASC) : void
    {
        if (Chars::Contains("->", $column))
        {
            $this->Order[] = str_replace("->", ".", $column)." $order";
        }
        else
        {
            $this->Order[] = "$this->TableName.$column $order";
        }
    }
    function Limit(int $limit) : void
    {
        $this->Limit = $limit;
    }
    function Page(int $page, int $itemCount) : void
    {
        $page = $page == 0 ? 1 : $page;
        $page = ($page - 1) * $itemCount;
        $this->Page = $page.", ".$itemCount;
    }
    function PageCount(int $itemCount) : int
    {
        $itemCount = $itemCount == 0 ? 1 : $itemCount;
        return ceil($this->Count() / $itemCount);
    }
    function Clear() : void
    {
        $this->Where = array();
        $this->Join = array();
        $this->JoinSchema = array();
        $this->Order = array();
        $this->Limit = null;
    }
    function Insert() : void
    {
        $columns = array();
        $values = array();
        $this->dat_insert_time = Date::Now();
        $this->dat_update_time = null;
        foreach ($this->Columns as $column)
        {
            $name = $column->getName();
            $value = $column->getValue($this);
            if ($name != "id" && !is_null($value))
            {
                $columns[] = $name;
                $values[] = "'".$this->Sanitize($value)."'";
            }
        }
        if (count($columns) > 0)
        {
            $query = "INSERT INTO $this->TableName(".join(", ", $columns).") VALUES(".join(", ", $values).");";
            $this->Execute($query);
            $this->SelectSingle();
        }
    }
    function Update() : void
    {
        $updateValues = array();
        $this->dat_insert_time = null;
        $this->dat_update_time = Date::Now();
        foreach ($this->Columns as $column)
        {
            $name = $column->getName();
            $value = $column->getValue($this);
            if ($name != "id" && !is_null($value))
            {
                $updateValues[] = $name." = '".$this->Sanitize($value)."'";
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
                $property->setValue($this, $model->$name);
            }
        }
    }
    static function AddSchema(string $schemaName) : void
    {
        require_once "Schemas/$schemaName.php";
    }
}
class InformationSchemaTables extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("INFORMATION_SCHEMA.TABLES", false);
    }
    public $TABLE_NAME;
    public $TABLE_SCHEMA;
    public $COLUMN_NAME;
}
class InformationSchemaColumns extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("INFORMATION_SCHEMA.COLUMNS", false);
    }
    public $TABLE_NAME;
    public $TABLE_SCHEMA;
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
        $this->str_session_id = Chars::Random(50);
        while ($this->Count() != 0)
        {
            $this->Clear();
            $this->str_session_id = Chars::Random(50);
        }
        $this->dat_session_time = Date::Now();
        $this->Insert();
        return $this->str_session_id;
    }
    /**
     * Populate $sessionKey to add / update a new key for the current session.
     */
    function CheckSession(string $sessionKey = "") : bool 
    {
        if (!Obj::HasValue($this->str_session_id))
        {
            return false;
        }
        $this->Where("dat_session_time", DB::GreaterThan, Date::Now(-Settings::Session()));
        if ($this->Count() == 0)
        {
            return false;
        }
        $this->Clear();
        if (Obj::HasValue($sessionKey))
        {
            $this->str_session_key = $sessionKey;
        }
        $this->dat_session_time = Date::Now();
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