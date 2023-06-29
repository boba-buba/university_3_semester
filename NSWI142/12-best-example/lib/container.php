<?php

/**
 * Component container which implements Dependency Injection pattern.
 */
class Container
{
	private $config;
	private $cache = [];
	private $interfaces = [];	// known interfaces (interface name => list of known classes)
	private $pending = [];		// components being instantiated (in progress)

	/**
	 * Initialize the container using config values.
	 * The configuration of all components is captures, so it can be used later.
	 */
	public function init(array $config)
	{
		$this->config = $config;

		// Get the known classes from configuration.
		$knownClasses = [];
		if (!empty($config['Container'])) {
			$knownClasses = $config['Container'];
		}

		// Get interfaces of known classes...
		foreach ($knownClasses as $class) {
			$refClass = new ReflectionClass($class);
			foreach ($refClass->getInterfaceNames() as $interface) {
				if (empty($this->interfaces[$interface]))
					$this->interfaces[$interface] = [];		// laying the foundation
				$this->interfaces[$interface][] = $class;
			}
		}
	}

	/**
	 * Gets a component (class) that implements given interface.
	 * In this simple implementation, the first such class is returned.
	 */
	public function getByInterface(string $interface)
	{
		if (empty($this->interfaces[$interface])) return null;
		$className = reset($this->interfaces[$interface]);	// get first class satisfying an interface
		return $this->getByName($className);
	}

	/**
	 * Internal function that resolves given parameters and tries to satisfy them with components.
	 * When the components does not exist, they are looked up and instantiated automatically.
	 */
	private function prepareConstructorArgs(array $parameters, $name)
	{
		$args = [];
		foreach ($parameters as $parameter) {
			$type = $parameter->getType();
			if (!$type)
				return null;	// no way how to satisfy typeless parameter of a constructor	
			$type = (string)$type;

			if ($type == 'array') 			// array is for config parameters injection
				$value = (!empty($this->config[$name])) ? $this->config[$name] : [];
			elseif ($type == 'Container')	// container can inject itself
				$value = $this;
			else							// a class or an interface needs to be satisfied
				$value = ($type[0] === 'I') ? $this->getByInterface($type) : $this->getByName($type);
			
			if ($value === null) return null;	// cannot satisfy dependency
			$args[$parameter->getName()] = $value;
		}
		return $args;
	}

	/**
	 * Get a component (class) by its class name.
	 * This is less typical, normally we prefer get-by-interface approach.
	 */
	public function getByName(string $name)
	{
		if (!empty($this->cache[$name]))
			return $this->cache[$name];

		if (!empty($this->pending[$name]))
			throw new Exception("Cyclic injection dependency detected!");

		$result = null;
		$this->pending[$name] = true;
		try {
			// Use reflection to scan for the constructor signature...
			$refClass = new ReflectionClass($name);
			$refConstruct = $refClass->getConstructor();

			$args = ($refConstruct) ? $this->prepareConstructorArgs($refConstruct->getParameters(), $name) : [];
			if ($args !== null) {
				$result = $refClass->newInstanceArgs($args);
				$this->cache[$name] = $result;
			}
		}
		catch (ReflectionException $e) {
			$result = null;
		}

		unset($this->pending[$name]);
		return $result;
	}
}
