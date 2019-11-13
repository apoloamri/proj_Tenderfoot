<?php
class Routing
{
	private $RequestMethod, $RequestUri, $Routed = false;
	function __construct()
	{
		$this->RequestMethod = $_SERVER["REQUEST_METHOD"];
		$this->RequestUri = explode("?", $_SERVER["REQUEST_URI"])[0];
	}
	function Map(string $route, string $controller, string $action) : void
	{
		if (!$this->Routed)
		{
			$method;
			$valid = false;
			$routeParameters = array();
			$routeRequest = $this->RequestUri;
			if ($route != "*")
			{
				$route = explode(" ", $route);
				$routeRequest = array_filter(explode("/", $routeRequest));
				$method = $route[0];
				if (strpos($route[1], "/") !== false)
				{
					$route = array_filter(explode("/", $route[1]));
					foreach ($route as $index => $segment)
					{
						if (substr($segment, 0, 1) == "[")
						{
							$param = str_replace("[", "", $segment);
							$param = str_replace("]", "", $param);
							if (array_key_exists($index, $routeRequest))
							{
								$routeParameters[$param] = $routeRequest[$index];
								$routeRequest[$index] = "";
							}
							$route[$index] = "";
						}
						else if (!array_key_exists($index, $routeRequest) || $segment != $routeRequest[$index])
						{
							$valid = false;
							break;
						}
					}
					$route = "/".join("/", array_filter($route));
					$routeRequest = "/".join("/", array_filter($routeRequest));
				}
			}
			if ($route == "*" || ($route == $routeRequest && $method == $this->RequestMethod))
			{
				foreach ($routeParameters as $key => $value)
				{
					$_REQUEST[$key] = $value;
				}
				$controllerPath = "Controllers/".$controller."Controller.php";
				if (file_exists($controllerPath))
				{
					require_once $controllerPath;
					$controllerName = $controller."Controller";
					$controllerBase = new $controllerName;
					if (method_exists($controllerBase, $action))
					{
						$this->Routed = true;
						$controllerBase->{$action}();
					}
				}
			}
		}
	}
}
?>