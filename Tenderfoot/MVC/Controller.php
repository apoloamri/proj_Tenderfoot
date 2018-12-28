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
			if (_::HasValue($modelLocation))
			{
				$controller = $modelLocation;
			}
			require_once "Models/$controller/$model.php";
			$this->Model = new $model;
		}
		$this->Model->URI = explode("/", $_SERVER["REQUEST_URI"]);
		$this->Model->Environment = $this->Environment;
		$this->Model->Deployment = Settings::Deploy();
		$this->Validate();
	}
	protected function Execute(string $method) : void
	{
		if ($this->Model->IsValid)
		{
			$this->Transact();
			switch ($_SERVER['REQUEST_METHOD'])
			{
				case Http::Get:
					$this->Model->Map();
					break;
				case Http::Post:
				case Http::Put:
				case Http::Delete:
					$this->Model->Handle();
					break;
			}
			$this->Commit();
		}
	}
	protected function View(string $viewName) : void
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
			header("Content-Type: text/html");
			$model->InitiatePage($this, $viewName);
			$view = new View($this, $model, "app");
			if ($view->NotFound)
			{
				// require_once $layout;
			}
			else
			{
				echo $view->View;
			}
		}
	}
	protected function Redirect(string $url) : void
	{
		header('Location: '.$url);
		die();
	}
	protected function Json(string ...$fields) : void
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
		echo json_encode($jsonArray, JSON_PRETTY_PRINT);
	}
}
class Http
{
	const Get = "GET";
	const Post = "POST";
	const Put = "PUT";
	const Delete = "DELETE";
	const Option = "OPTION";
}
?>