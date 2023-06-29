<?php


interface IView
{
	/**
	 * Set argument, that can be used in the template when rendering.
	 */
	public function setArg(string $name, $value);

	/**
	 * Take a template, render it and send the result to the output.
	 */
	public function render(string $template);
}

/**
 * Base for a class that holds arguments, which has to be sanitized for HTML injection.
 */
class ArgsAccessor implements ArrayAccess, Iterator
{
	protected $args = [];

	public function __construct(array $args)
	{
		$this->args = $args;
	}

	public function __isset($name)
	{
		return isset($this->args[$name]);
	}

	/**
	 * The main getter method, which sanitizes the arguments being yielded.
	 */
	protected function get($name)
	{
		if (!isset($this->args[$name])) return '';
		if (is_array($this->args[$name])) {
			if (!$this->args[$name]) return [];
			return new ArgsAccessor($this->args[$name]);
		}
		else
			return htmlspecialchars($this->args[$name], ENT_QUOTES);		
	}

	public function __get($name)
	{
		return $this->get($name);
	}

	public function offsetExists($offset)
	{
		return isset($this->args[$name]);
	}

	public function offsetGet($offset)
	{
		return $this->get($name);		
	}

	public function offsetSet($offset, $value)
	{
		throw new Exception("The array accessor is immutable.");
	}

	public function offsetUnset($offset)
	{
		throw new Exception("The array accessor is immutable.");		
	}
	
	public function current()
	{
		return $this->get(key($this->args));
	}

	public function key()
	{
		return key($this->args);
	}

	public function next()
	{
		next($this->args);
	}

	public function rewind()
	{
		reset($this->args);
	}

	public function valid()
	{
		return $this->key() !== null;
	}
}


/**
 * View is implemented as a simple provider of arguments which also renders templates.
 */
class View extends ArgsAccessor implements IView
{
	private $layout = null;
	private $path = './templates';
	private $template = null;
	private $router;


	public function __construct(array $config, IRouter $router)
	{
		$this->router = $router;
		foreach (['path', 'layout', 'args'] as $name) {
			if (isset($config[$name]))
				$this->$name = $config[$name];
		}
	}

	/**
	 * Add another argument for the template.
	 */
	public function setArg(string $name, $value)
	{
		$this->args[$name] = $value;
	}
	

	/**
	 * Render given template. If its missing, the template name is determined from the page name.
	 */
	public function render(string $template = '')
	{
		if (!$template && !empty($this->args['page']))
			$template = $this->args['page'];
		if (!$template)
			throw new Exception("No template to render.");
		$this->template = $template;

		if ($this->layout) {
			$file = "$this->path/$this->layout.php";
			if (!is_readable($file) || !is_file($file))
				throw new Exception("Layout template is not present.");
			require($file);
		}
		else
			$this->content();
		$this->template = null;
	}

	
	/**
	 * Render the main content. This is typically called from parent (layout) template.
	 */
	private function content()
	{
		if (!$this->template)
			throw new Exception("Internal error. View has no template.");
		$file = "$this->path/$this->template.php";
		if (!is_readable($file) || !is_file($file))
			throw new Exception("Given template is not present.");

		require($file);
	}

	private function getRaw($name)
	{
		return isset($this->args[$name]) ? $this->args[$name] : '';
	}

	private function url($page = null, $action = null, $params = [])
	{
		if (!$page) {
			if (!$this->args['page'])
				throw new Exception("Unable to create an URL without page.");
			$page = $this->args['page'];
		}
		return htmlspecialchars($this->router->url($page, $action, $params));
	}
}

