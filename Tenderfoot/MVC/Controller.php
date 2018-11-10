<?php
require_once "Tenderfoot/Lib/BaseController.php";
class Controller extends BaseController
{
	protected function Initiate(string $model = null, string $modelLocation = null) : void
	{
		$this->SetTimeZone();
		if ($model == null)
		{
			$this->Model = new Model();
		}
		else
		{
			$controller = str_replace("Controller", "", get_class($this));
			if (HasValue($modelLocation))
			{
				$controller = $modelLocation;
			}
			require_once "Models/$controller/$model.php";
			$this->Model = new $model;
		}
		$this->Model->URI = explode("/", $_SERVER["REQUEST_URI"]);
		$this->Model->Environment = $this->Environment;
		$this->Validate();
	}
	protected function Execute(string $method) : void
	{
		try
		{
			if ($this->Model->IsValid)
			{
				switch ($_SERVER['REQUEST_METHOD'])
				{
					case "GET":
						$this->Model->Map();
						break;
					case "POST":
					case "PUT":
					case "DELETE":
						$this->Model->Handle();
						break;
				}
			}
		}
		catch (Exception $ex)
		{
			echo "A system error occured!\n",  $ex->getMessage(), "\n";
		}
	}
	protected function View(string $viewName) : void
	{
		try
		{
			$layout = "Views/app.php";
			if ($this->Model == null)
			{
				$model = new Model();
			}
			else
			{
				$model = $this->Model;
			}
			if (file_exists($layout))
			{
				$model->InitiatePage(get_class($this), $viewName);
				header("Content-Type: text/html");
				require_once $layout;
			}
		}
		catch (Exception $ex)
		{
			echo "A system error occured!\n",  $ex->getMessage(), "\n";
		}
	}
	protected function Redirect(string $url) : void
	{
		header('Location: '.$url);
		die();
	}
	protected function Json(string ...$fields) : void
	{
		try 
		{
			array_push($fields, "IsValid");
			array_push($fields, "Messages");
			$reflect = new ReflectionClass($this->Model);
			$jsonArray = array();
			foreach ($fields as $field)
			{
				if (property_exists($this->Model, $field))
				{
					$jsonArray[$field] = $reflect->getProperty($field)->getValue($this->Model);
				}
			}
			header("Content-Type: application/json");
			echo json_encode($jsonArray);
		}
		catch (Exception $ex)
		{
			echo "A system error occured!\n",  $ex->getMessage(), "\n";
		}
	}
}
?>