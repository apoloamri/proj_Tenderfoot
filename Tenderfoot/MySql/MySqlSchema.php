<?php 
require_once "Tenderfoot/Lib/BaseMySqlSchema.php";
class MySqlSchema extends BaseMySqlSchema
{
    /**
     * $column - Add column properties for the table. Use 'new Column(name, type, notNull, length)'.
     */
    function __construct(?Column ...$columns)
    {
        $this->InitializeConnection();
        $this->Columns = $columns;
        $this->TableName = $this->TableName ?? strtolower(get_class($this));
        if (TempData::Get("migrate"))
        {
            $this->CreateTable();
            $this->UpdateColumns();
        }
        $reflect = new ReflectionClass($this);
        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $this->Properties = $properties;
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
        $this->insert_time = Date::Now();
        $this->update_time = null;
        foreach ($this->Properties as $column)
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
        $this->insert_time = null;
        $this->update_time = Date::Now();
        foreach ($this->Properties as $column)
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
    static function AddSchema(string $schemaName) : void
    {
        require_once "Schemas/$schemaName.php";
    }
}
class InformationSchemaTables extends MySqlSchema
{
    function __construct()
    {
        $this->TableName = "INFORMATION_SCHEMA.TABLES";
        parent::__construct();
    }
    public $TABLE_NAME;
    public $TABLE_SCHEMA;
    public $COLUMN_NAME;
}
class InformationSchemaColumns extends MySqlSchema
{
    function __construct()
    {
        $this->TableName = "INFORMATION_SCHEMA.COLUMNS";
        parent::__construct();
    }
    public $TABLE_NAME;
    public $TABLE_SCHEMA;
    public $COLUMN_NAME;
}
class Sessions extends MySqlSchema
{
    function __construct()
    {
        parent::__construct(
            new Column("session_id", ColumnProp::VaryingChars, true, 50),
            new Column("session_key", ColumnProp::VaryingChars, true, 50),
            new Column("session_time", ColumnProp::DateTime, true)
        );
    }
    public $session_id;
    public $session_key;
    public $session_time;
    function GetSession() : string
    {
        if ($this->CheckSession())
        {
            return $this->session_id;
        }
        $this->session_id = Chars::Random(50);
        while ($this->Count() != 0)
        {
            $this->Clear();
            $this->session_id = Chars::Random(50);
        }
        $this->session_time = Date::Now();
        $this->Insert();
        return $this->session_id;
    }
    /**
     * Populate $sessionKey to add / update a new key for the current session.
     */
    function CheckSession(?string $sessionKey) : bool 
    {
        if (!Obj::HasValue($this->session_id))
        {
            return false;
        }
        $this->Where("session_time", DB::GreaterThan, Date::Now(-Settings::Session()));
        if ($this->Count() == 0)
        {
            return false;
        }
        $this->Clear();
        if (Obj::HasValue($sessionKey))
        {
            $this->session_key = $sessionKey;
        }
        $this->session_time = Date::Now();
        $this->Where("session_id", DB::Equal, $this->session_id);
        $this->Update();
        return true;
    }
}
class Accesses extends MySqlSchema
{
    function __construct()
    {
        parent::__construct(
            new Column("access_key", ColumnProp::VaryingChars, true, 255),
            new Column("password", ColumnProp::VaryingChars, true, 255)
        );
    }
    public $access_key;
    public $password;
}
?>