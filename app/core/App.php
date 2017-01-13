<?php

class App
{

	protected $controller = "home";
	protected $method = "index";
	protected $params = Array(); // maybe want new stdClass() instead, per https://stackoverflow.com/questions/1434368/how-to-define-an-empty-object-in-php

	public function __construct() {
		$url = $this->parseUrl();

		if (file_exists("../app/controllers/{$url[0]}.php")) {
			$this->controller = $url[0];
			unset($url[0]);
		}

		require_once("../app/controllers/{$this->controller}.php");
		$this->controller = new $this->controller;

		if (isset($url[1])) {
			if (method_exists($this->controller, $url[1])) {
				$this->method = $url[1];
				unset($url[1]);
			}
		}

		$this->params = $url ? array_values($url) : []; // again with the dubious []

		call_user_func_array([$this->controller, $this->method], $this->params);

	}

	public function parseUrl() {
		if(isset($_GET['url'])) {
				return $url = explode("/",filter_var(rtrim($_GET['url'], "/"), FILTER_SANITIZE_URL));
		}
	}
}

?>
