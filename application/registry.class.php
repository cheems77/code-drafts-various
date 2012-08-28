<?php

/**
 * 'Registry' Design Pattern to hold and persist objects/values
 *
 *
 * @author Chima Ijeoma (chimai@gmail.com)
 *
 **/

class Registry {

	private $vars = array();

	/**
	 *
	 * @param string $index
	 *
	 * @param mixed $value
	 *
	 * @return void
	 *
	 */
	public function __set($index, $value) {
		$this -> vars[$index] = $value;
	}

	/**
	 *
	 * @param mixed $index
	 *
	 * @return mixed
	 *
	 */
	public function __get($index) {
		return $this -> vars[$index];
	}

}
?>