<?php


/**
 * URL handler
 * It dispatches request based on URL parameters and create correct URLs for the whole application.
 */
interface IRouter
{
	/**
	 * Get routing data from URL, instantiate controller, and dispatch the request.
	 */
	public function dispatch();

	/**
	 * Create URL for particular page, action, and parameters.
	 */
	public function url($page, $action = null, $params = []);
}



class Router implements IRouter
{
	private $default = 'Default';	// name of default page
	private $base = '';				// URL prefix
	private $container;
	
	public function __construct(array $config, Container $container)
	{
		$this->container = $container;
		foreach (['default', 'base'] as $name) {
			if (isset($config[$name]))
				$this->$name = $config[$name];
		}
	}

	/**
	 * Uses 'page' and 'action' parameters from URL to determine controller and its method.
	 */
	public function dispatch()
	{
		// Get the controller object...
		$page = empty($_GET['page']) ? $this->default : trim($_GET['page']);
		$controller = $this->container->getByName($page . 'Controller');
		if (!$controller)
			throw new Exception("Unknown page '$page'.");
		
		// Get the HTTP request method (part of the action method name)
		if (empty($_SERVER['REQUEST_METHOD']))
			throw new Exception("Unknown request method!");
		$reqMethod = strtolower($_SERVER['REQUEST_METHOD']);

		// Get the action and assemble the target method name...
		$action = empty($_GET['action']) ? '' : ucfirst(strtolower(trim($_GET['action'])));
		$method = $reqMethod . $action . 'Action';
		if (!method_exists($controller, $method))
			throw new Exception("Invalid action '$action' requested on page '$page'.");
		
		// Invoke the target method on the controller
		if ($reqMethod === 'post')
			$controller->$method($_POST, $_GET);
		else
			$controller->$method($_GET);
	}
	
	/**
	 * Create URL for particular page, action, and parameters.
	 */
	public function url($page, $action = null, $params = [])
	{
		if ($page instanceof BaseController)
			$page = $page->getName();
		$url = "$this->base/$page";
		
		if ($action)
			$url .= "/$action";
		
		if ($params)
			$url .= '?' . http_build_query($params);
		
		return $url;
	}		
}
