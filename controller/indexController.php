<?php

/**
 * Controller class as part of MVC framework
 *
 * Used set up initial page view of PIN generation tool
 *
 * @author Chima Ijeoma (chimai@gmail.com)
 *
**/

Class indexController Extends baseController {

	protected $registry;

	public function index() {
		
		/*** an initial check of the database to see if all our allocated pins have been emitted ***/
		$pin = new pin($this->registry);
		
		try {
		
			$_allocated_pins = $pin->getAllocatedPins();
		
			if ($_allocated_pins) {
				
				if ( count($_allocated_pins) == __MAX_PIN) {
				
				/*** clear down our db and start again  ***/
				$pin->clearPins();
				}
			}
			
		} catch (exception $ex) {
			$this->registry->template->status("An error has occurred: Error: " . $ex->getMessage());
		}
				
		/*** set a template variable ***/
		$this -> registry -> template -> page_title = 'Pin Generator tool';
		
		/*** load our 'pin quantity' drop down with the number of values to create ***/
		$this -> registry -> template -> pin_quantity = __MAX_PIN_QUANTITY;
		
		/*** load the index template ***/
		$this -> registry -> template -> show('index');
	}

}
?>
