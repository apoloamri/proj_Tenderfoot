<?php 
class BaseMySqlSchema
{
    public $id; 
    public $dat_insert_time;
    public $dat_update_time;
    protected $Columns;
    protected $Connect;
    protected $TableName;
    protected function InitializeConnection()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        if (!array_key_exists(".settings.connection", $GLOBALS))
		{
            try
            {
                $cachedConnection = mysqli_connect(Settings::Host(), Settings::User(), Settings::Password(), Settings::Database());
                $GLOBALS[".settings.connection"] = $cachedConnection;
                $this->Connect = $cachedConnection;
            }
            catch (exception $ex)
            {
                echo "Database <b>".Settings::Database()."</b> is not ready yet.";
                die();
            }
		}
		else
		{
			$this->Connect = $GLOBALS[".settings.connection"];
		}
    }
    function Execute(string $query)
    {
        $result = mysqli_query($this->Connect, $query);
        if (!$result)
        {
            echo "[ERROR FOUND ON QUERY] $query";
            return null;
        }
        else
        {
            return $result;
        }
    }
    protected function CreateTable() : void
    {
        $table = new InformationSchemaTables();
        $table->TABLE_SCHEMA = Settings::Database();
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
            $this->Migration($query);
        }
    }
    protected function UpdateColumns() : void
    {
        $alterColumns = array();
        foreach ($this->Columns as $property)
        {
            $columnName = $property->getName();
            $table = new InformationSchemaColumns();
            $table->TABLE_SCHEMA = Settings::Database();
            $table->TABLE_NAME = $this->TableName;
            $table->COLUMN_NAME = $columnName;
            if ($table->Count("COLUMN_NAME") == 0)
            {
                $column = $this->GetColumnType($property);
                $alterColumns[] = "ALTER TABLE $this->TableName ADD COLUMN $column;";
            }
        }
        if (count($alterColumns) > 0)
        {
            foreach ($alterColumns as $query)
            {
                $this->Execute($query);
                $this->Migration($query);
            }
        }
    }
    protected function GetColumnType(ReflectionProperty $column) : string
    {
        $returnString;
        $name = $column->getName();
        $value = $column->getValue($this);
        $isArray = is_array($value) ? "[]" : "";
        if ($name == "id")
        {
            return "id SERIAl PRIMARY KEY";
        }
        else
        {
            switch (substr($name, 0, 4))
            {
                case "str_":
                    $returnString =  "$name VARCHAR(255)";
                    break;
                case "int_":
                    $returnString =  "$name INT";
                    break;
                case "dbl_":
                    $returnString =  "$name DOUBLE";
                    break;
                case "sml_":
                    $returnString =  "$name SMALLINT";
                    break;
                case "big_":
                    $returnString =  "$name BIGINT";
                    break;
                case "dat_":
                    $returnString =  "$name DATETIME";
                    break;
                case "txt_":
                    $returnString =  "$name TEXT";
                    break;
                default:
                    $returnString = "$name VARCHAR(255)";
                    break;
            }
        }
        return $returnString.$isArray;
    }
    function GetQuery(array $columns) : string
    {
        $compiledColumns = array();
        if (count($columns) > 0)
        {
            foreach ($columns as $column)
            {
                $compiledColumns[] = 
                    Chars::Contains("->", $column) ?
                    str_replace("->", ".", $column) :
                    "$this->TableName.$column";
            }
        }
        else
        {
            foreach ($this->Columns as $property)
            {
                $columnName = $property->getName();
                $compiledColumns[] = "$this->TableName.$columnName";
            }
            if (count($this->JoinSchema) > 0)
            {
                foreach ($this->JoinSchema as $schema)
                {
                    foreach ($schema->Columns as $column)
                    {
                        $columnName = $column->getName();
                        $compiledColumns[] = 
                            $columnName == "id" || in_array($columnName, $compiledColumns) ?
                            "$schema->TableName.$columnName AS '$schema->TableName"."->$columnName'" :
                            "$schema->TableName.$columnName";
                    }
                }
            }
        }
        $select = join(", ", $compiledColumns);
        $join = $this->GetJoin();
        $where = $this->GetWhere(true);
        $order = $this->GetOrder();
        $group = $this->GetGroup();
        $limit = $this->GetLimit();
        return "SELECT $select FROM $this->TableName $join $where $group $order $limit";
    }
    protected $Where = array();
    protected $Wheres = array();
    protected function GetWhere(bool $isSelect = false, bool $getWheres = true) : string
    {
        $entityValues = array();
        if ($isSelect)
        {
            foreach ($this->Columns as $column)
            {
                $value = $column->getValue($this);
                if ($value != null)
                {
                    $entityValues[] = "$this->TableName.".$column->getName()." = '".$this->Sanitize($value)."' ".DB::AND;
                }
            }
        }
        if ($getWheres && count($this->Wheres) > 0)
        {
            $this->Where[] = trim(join(" ", $entityValues)." ".join(" ", $this->Wheres))." ";
        }
        if (count($this->Where) > 0 || count($entityValues) > 0)
        {
            $where = "WHERE ".trim(join(" ", $entityValues)." ".join(" ", $this->Where))." ";
            $where = trim($where);
            $constants = new DB();
            $constants = new ReflectionClass(get_class($constants));
            $constants = $constants->getConstants();
            foreach ($constants as $constant => $value)
            {
                $where = chop($where, $constant);
            }
            return $where;
        }
        return "";
    }
    protected function GetWheres() : string
    {
        return trim(join(" ", $entityValues)." ".join(" ", $this->Wheres))." ";
    }
    protected $Join = array();
    protected $JoinSchema = array();
    protected function GetJoin() : string
    {
        if (count($this->Join) > 0)
        {
            return join(" ", $this->Join);
        }
        return "";
    }
    protected $Group = array();
    protected function GetGroup() : string
    {
        if (count($this->Group) > 0)
        {
            $group = join(", ", $this->Group);
            return "GROUP BY $group";
        }
        return "";
    }
    protected $Order = array();
    protected function GetOrder() : string
    {
        if (count($this->Order) > 0)
        {
            $order = join(", ", $this->Order);
            return "ORDER BY $order";
        }
        return "";
    }
    protected $Page;
    protected $Limit;
    protected function GetLimit() : string
    {
        if (Obj::HasValue($this->Page))
        {
            return "LIMIT $this->Page";
        }
        else if (Obj::HasValue($this->Limit))
        {
            return "LIMIT $this->Limit";
        }
        return "";
    }
    protected function Sanitize($value) : string 
    {
        return mysqli_escape_string($this->Connect, $value);
    }
    private function Migration(string $query)
    {
        $migrationLog = "-- ".Date::Now()."\r\n$query\r\n";
        file_put_contents("migrations.txt", $migrationLog.PHP_EOL , FILE_APPEND | LOCK_EX);   
    }
}
class DB
{
    const AND = "AND";
    const OR = "OR";
    const Equal = "=";
    const NotEqual = "!=";
    const GreaterThan = ">";
    const GreaterThanEqual = ">=";
    const LessThan = "<";
    const LessThanEqual = "<=";
    const Like = "LIKE";
    const NotLike = "NOT LIKE";
    const ASC = "ASC";
    const DESC = "DESC";
}
?>