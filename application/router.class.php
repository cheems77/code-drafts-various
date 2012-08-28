<?php

/**
 * 'Registry' Design Pattern to hold and persist objects/values
 *
 *
 * @author Chima Ijeoma (chimai@gmail.com)
 *
 **/
 
class router {

	private $registry;

	private $path;

	private $args = array();

	public $file;

	public $controller;

	public $action;

	function __construct($registry) {
		$this -> registry = $registry;
	}

	/**
	 *
	 * set the path for the MVC application
	 * @param string $path
	 * @return void
	 *
	 */
	function setPath($path) {

		if (is_dir($path) == false) { // check if path is a directory
			throw new Exception('Invalid controller path: `' . $path . '`');
		}
		$this -> path = $path; // set the path
	}

	/**
	 *
	 * load the controller
	 *
	 * @return void
	 *
	 */
	public function loader() {

		$this -> getController(); // check the route

		if (is_readable($this -> file) == false) { // send to 404 error page if file is not available
			$this -> file = $this -> path . '/error404.php';
			$this -> controller = 'error404';
		}

		include $this -> file; // include the relevant controller

		$class = $this -> controller . 'Controller'; // new controller class instance
		$controller = new $class($this -> registry);

		// check if the action is callable
		if (is_callable(array($controller, $this -> action)) == false) {
			$action = 'index';
		} else {
			$action = $this -> action;
		}
		// run the action
		$controller -> $action();
	}

	/**
	 *
	 * @return void
	 *
	 */
	private function getController() {

		/*** get the route from the url ***/
		$route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

		if (empty($route)) {
			$route = 'index';
		} else {
			// get the parts of the route
			$parts = explode('/', $route);
			$this -> controller = $parts[0];
			if (isset($parts[1])) {
				$this -> action = $parts[1];
			}
		}

		if (empty($this -> controller)) {
			$this -> controller = 'index';
		}

		// Get the 'action'
		if (empty($this -> action)) {
			$this -> action = 'index';
		}

		// set the file path
		$this -> file = $this -> path . '/' . $this -> controller . 'Controller.php';
	}

}
?>
