<?php


interface INotesModel
{
	/**
	 * Return data as an array (id => text).
	 */
	public function getAll();

	/**
	 * Set (modify) one item by its id.
	 */
	public function set($id, $text);

	/**
	 * Add a new item at the end (with new id).
	 */
	public function add($text);

	/**
	 * Delete item by its id.
	 */
	public function delete($id);		
}


/**
 * Class representing model of the application.
 * It manages access to the text data file.
 * @todo JSON would be better.
 */
class NotesModel implements INotesModel
{
	private $path;				///< Path to the storage file.
	private $cache = null;		///< Entire file cached line by line.


	public function __construct(array $config)
	{
		if (empty($config['data_file']))
			throw new Exception("No data file path provided to the Model.");
		$this->path = realpath($config['data_file']);
		$dir = dirname($this->path);
		if (!is_dir($dir) && !@mkdir($dir, 0777, true))
			throw new Exception("Unable to create data directory for data file $this->path.");
	}


	private function load()
	{
		if (file_exists($this->path))
			$this->cache = file($this->path, FILE_IGNORE_NEW_LINES);
		else
			$this->cache = [];
	}

	private function save()
	{
		if ($this->cache === null) return;
		file_put_contents($this->path, implode($this->cache, "\n"));
	}

	private static function sanitize($text)
	{
		return str_replace("\n", "", $text);
	}

	public function get($id)
	{
		if ($this->cache === null) $this->load();
		return empty($this->cache[$id]) ? '' : $this->cache[$id];
	}

	public function getAll()
	{
		if ($this->cache === null) $this->load();
		return $this->cache;
	}

	public function set($id, $text)
	{
		$id = (int)$id;
		if ($id < 0 || $id > 1000000)
			throw new Exception("ID $id is out of range.");

		if ($this->cache === null) $this->load();
		while (!array_key_exists($id, $this->cache))
			$this->cache[] = '';
		
		$this->cache[$id] = $text;
		$this->save();		
	}

	public function add($text)
	{
		if ($this->cache === null) $this->load();
		$this->cache[] = $text;
		$this->save();
	}

	public function delete($id)
	{
		if ($this->cache === null) $this->load();
		if (array_key_exists($id, $this->cache)) {
			unset($this->cache[$id]);
			$this->save();
			$this->cache = null;	// a reload is required since the IDs have changed
		}
	}
}
