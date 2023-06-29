<?php

/**
 * Base class for all controllers.
 */
class BaseController
{
	protected $view;
	protected $router;

	public function __construct(IView $view, IRouter $router)
	{
		$view->setArg('page', $this->getName());	// make sure the view knows who is in charge
		$this->view = $view;
		$this->router = $router;
	}

	/**
	 * Get the name of the controller (used for URL identification).
	 */
	public function getName()
	{
		$name = get_class($this);
		return str_replace('Controller', '', $name);
	}


	/**
	 * Internal implementation of HTTP redirect.
	 */
	protected function redirect($page = null, $action = null, $params = [])
	{
		if (!$page) $page = $this->getName();
		$url = $this->router->url($page, $action, $param);
		header("Location: $url");
		exit;
	}
}
