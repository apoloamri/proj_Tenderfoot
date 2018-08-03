<?php 
class BaseSchema
{
    public $id;
    protected $TableName;
    protected $Columns;
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
        $name = $column->getName();
        $type = substr($name, 0, 4);
        if ($name == "id")
        {
            return "id serial NOT NULL PRIMARY KEY";
        }
        else
        {
            switch ($type)
            {
                case "str_":
                    return "$name character varying";
                    break;
                case "num_":
                    return "$name integer";
                    break;
                case "sml_":
                    return "$name smallint";
                    break;
                case "big_":
                    return "$name bigint";
                    break;
                case "dat_":
                    return "$name timestamp without time zone";
                    break;
                case "txt_":
                    return "$name text";
                    break;
                default:
                    return "$name character varying";
                    break;
            }
        }
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
}
?>