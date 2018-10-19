<?php 
class BaseMySqlSchema
{
    public $id;
    protected $Columns, $Connect, $Join, $TableName;
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
            return "id serial NOT NULL PRIMARY KEY";
        }
        else
        {
            switch (substr($name, 0, 4))
            {
                case "str_":
                    $returnString =  "$name character varying";
                    break;
                case "num_":
                    $returnString =  "$name integer";
                    break;
                case "dbl_":
                    $returnString =  "$name double precision";
                    break;
                case "sml_":
                    $returnString =  "$name smallint";
                    break;
                case "big_":
                    $returnString =  "$name bigint";
                    break;
                case "dat_":
                    $returnString =  "$name timestamp without time zone";
                    break;
                case "txt_":
                    $returnString =  "$name text";
                    break;
                default:
                    $returnString = "$name character varying";
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
                    $entityValues[] = $column->getName()." = ".$this->MySqliEscapeLiteral($value)." ".DB::AND;
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
    protected $Limit;
    protected function GetLimit() : string
    {
        if (HasValue($this->Limit))
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