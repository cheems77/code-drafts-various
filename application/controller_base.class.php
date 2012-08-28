<?php

/**
 * Base class as part of MVC framework
 *
 * Super class from which child classes will inherit registry object
 * 
 *
 * @author Chima Ijeoma (chimai@gmail.com)
 *
**/

Abstract Class baseController {

	/**
	 * registry object
	**/
	protected $registry;

	function __construct($registry) {
		$this -> registry = $registry;
	}

	/**
	 * all controllers must contain an index method
	 */
	abstract function index();
}
?>
