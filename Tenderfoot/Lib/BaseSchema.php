<?php 
class BaseSchema
{
    public $id;
    protected $TableName, $Columns, $Limit;
    protected $Orders = array();
    protected $Parameters = array();
    protected $ParameterValues = array();
    protected function Execute(string $query, $useParams = true)
    {
        $pgConnect = pg_connect(Settings::ConnectionString());
        if ($useParams)
        {
            return pg_query_params($pgConnect, $query, $this->ParameterValues);
        }
        else
        {
            return pg_query($pgConnect, $query);
        }
    }
    protected function GetColumnType($column)
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
    protected function GetWhere()
    {
        if (count($this->Parameters) > 0)
        {
            $where = join(" ", $this->Parameters);
            $constants = new DB();
            $constants = new ReflectionClass(get_class($constants));
            $constants = $constants->getConstants();
            foreach ($constants as $constant => $value)
            {
                $where = chop($where, $constant);
            }
            return "WHERE $where";
        }
    }
    protected function GetOrders()
    {
        if (count($this->Orders) > 0)
        {
            $order = join(", ", $this->Orders);
            return "ORDER BY $order";
        }
    }
    protected function GetLimit()
    {
        if (!IsNullOrEmpty($this->Limit))
        {
            return "LIMIT $this->Limit";
        }
    }
    protected function AddParameterValue($value, string $statement)
    {
        $count = count($this->Parameters) + 1;
        $statement = sprintf($statement, "$".$count);
        array_push($this->Parameters, $statement);
        array_push($this->ParameterValues, $value);
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