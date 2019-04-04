<?php
require_once "Tenderfoot/Lib/BaseController.php";
class Controller extends BaseController
{
	protected function Authenticate() : void
	{
		$authorization = Headers::Get("Authorization");
		if (Chars::Contains("Bearer ", $authorization))
		{
			$authorization = str_replace("Bearer ", "", $authorization);
			$sessions = new Sessions();
			$token = $sessions->Get($authorization);
			if ($token->IsValid)
			{
				$this->SessionName = $token->Name;
				return;
			}
		}
		http_response_code(403);
		die();
	}
	protected function Initiate(string $model = null, string $modelLocation = null) : void
	{
		if ($model == null)
		{
			$this->Model = new Model();
		}
		else
		{
			$model = $model."Model";
			$controller = str_replace("Controller", "", get_class($this));
			if (Obj::HasValue($modelLocation))
			{
				$controller = $modelLocation;
			}
			require_once "Models/$controller/$model.php";
			$this->Model = new $model;
		}
		$this->Model->Uri = explode("/", $_SERVER["REQUEST_URI"]);
		$this->Model->Environment = $this->Environment;
		$this->Model->Deployment = Settings::Deploy();
		$this->Model->SessionName = $this->SessionName;
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
		$layout = "Views/app.html";
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
				echo "View not ready...";
			}
			else
			{
				echo $view->View;
			}
		}
	}
	protected function Redirect(string $url) : void
	{
		header("Location: ".$url, true, 303);
		die();
	}
	protected function Json(string ...$fields) : void
	{
		array_push($fields, "isValid");
		array_push($fields, "messages");
		$jsonArray = array();
		foreach ($fields as $field)
		{
			$fieldName = ucfirst($field);
			if (property_exists($this->Model, $fieldName))
			{
				$jsonArray[$field] = $this->Model->$fieldName;
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