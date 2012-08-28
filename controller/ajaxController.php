<?php

/**
 * Controller class as part of MVC framework
 *
 * Used determine which random pin numbers are available to generate - used
 * as part of an ajax lookup process
 *
 * @author Chima Ijeoma (chimai@gmail.com)
 *
**/

class ajaxController Extends baseController {

	/** 
	 * Used to return a JSON encoded array of available random pin numbers
	 * 
	 **/
	public function index() {
		
		$_row = array();
		$_json_pins = array(); // Container for our json encoded pin array
		$quantity = 0;
		
		if (isset($_REQUEST) && isset($_REQUEST['pinquantity']))		
		$quantity = $_REQUEST['pinquantity']; // determine the number of pins to generate				
		
		if ($quantity) {
			
			/*** initiate our pin class ***/
			$pin = new pin($this->registry);
		
			$_generated_pins = $pin->generatePin($quantity);
		
			// build our json array
			for ($x = 0; $x <= $quantity - 1; $x++){
				$row['id'] = $x;	
				$row['value'] = $_generated_pins[$x];
				$_json_pins[] = $row; 
			}
		} // End if
		echo json_encode($_json_pins); // json array is a callback parameter to the ajax code
	}
}