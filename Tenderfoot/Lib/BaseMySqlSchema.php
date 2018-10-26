<?php 
class BaseMySqlSchema
{
    public $id;
    protected $Columns, $Connect, $TableName;
    protected function InitializeConnection()
    {
        $connection = Settings::ConnectionString();
        if (!array_key_exists(".settings.connection", $GLOBALS))
		{
			$cachedConnection = mysqli_connect(Settings::Host(), Settings::User(), Settings::Password(), Settings::Database());
			$GLOBALS[".settings.connection"] = $cachedConnection;
			$this->Connect = $cachedConnection;
		}
		else
		{
			$this->Connect = $GLOBALS[".settings.connection"];
		}
    }
    protected function Execute(string $query)
    {
        return mysqli_query($this->Connect, $query);
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
                case "num_":
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
    protected $Where = array();
    protected function GetWhere(bool $isSelect = false) : string
    {
        $entityValues = array();
        if ($isSelect)
        {
            foreach ($this->Columns as $column)
            {
                $value = $column->getValue($this);
                if ($value != null)
                {
                    $entityValues[] = "$this->TableName.".$column->getName()." = '".$this->MySqliEscapeLiteral($value)."' ".DB::AND;
                }
            }
        }
        if (count($this->Where) > 0 || count($entityValues) > 0)
        {
            $where = trim(join(" ", $entityValues)." ".join(" ", $this->Where));
            $constants = new DB();
            $constants = new ReflectionClass(get_class($constants));
            $constants = $constants->getConstants();
            foreach ($constants as $constant => $value)
            {
                $where = chop($where, $constant);
            }
            return "WHERE $where";
        }
        return "";
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
        if (HasValue($this->Page))
        {
            return "LIMIT $this->Page";
        }
        else if (HasValue($this->Limit))
        {
            return "LIMIT $this->Limit";
        }
        return "";
    }
    protected function MySqliEscapeLiteral($value) : string 
    {
        return mysqli_escape_string($this->Connect, $value);
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