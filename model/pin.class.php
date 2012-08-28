<?php

/**
 * PIN Object class
 * 
 * @author Chima Ijeoma
 *
 **/

class pin {

	private $pid;
	private $registry;
	private $_pin_range = array();
	private $_allocated_pins = array();
	public $_blacklisted_pins = array();
	

	public function __construct($registry){
	
		$this->registry = $registry;
	
	}

	/**
	 * Use this to create a pin number with a zero padded prefix if required
	 * @example the number 16 represented as a four character string with padding is 0016
	 * @return string zero prefixed pin number
	 */
	private function str_padding($n){
		$padded_value = str_pad($n,__MAX_PIN_LENGTH,'0',STR_PAD_LEFT);
		return $padded_value;
	} 
	
	/**
	 * Create our initial pin number range
	 * @see str_padding - values from 0 - 999 may require zero padding - this function takes care of that
	 */
	public function generatePinRange(){
		
		$_range = range(0,__MAX_PIN,1);
		// Walk the $_range arrange and pad values with zero
		$_zero_padded_vals = array_map(array($this, 'str_padding'), $_range);
		
		return $_zero_padded_vals;
		
	}	
	
	/**
	 * Create an array of PINs that have already been emitted
	 */
    public function getAllocatedPins(){
    	
	  $_allocated_pins = array();
	  
	  $db = $this->registry->db;
	  
	  $conn = $db->getInstance();
		
      $sql_select = "SELECT * FROM pingen.pingen_pin";
      
      $result = $conn->Execute($sql_select,'');
		
	  while (!$result->EOF){
	  	
	    array_push($_allocated_pins,$result->fields['pid']);
		  
		  $result->moveNext();
      }
      return $_allocated_pins;
    }
	
	/**
	 * clear any allocated pins from the database
	 */
    public function clearPins(){
	  
	  $db = $this->registry->db;
	  
	  $conn = $db::getInstance();
		
      $sql_select = "DELETE FROM pingen.pingen_pin";
      
      $result = $conn->Execute($sql_select,'');
		
    }	
	
	/**
	 * Create an array of PINs that are not allowed for regular use
	 */
    public function getBlacklistedPins(){
	  
	  $_blacklisted_pins = array();
	  
	  $db = $this->registry->db;
	  
	  $conn = $db::getInstance();
		
      $sql_select = "SELECT * FROM pingen.pingen_pinblacklist";
      
      $result = $conn->Execute($sql_select,'');
		
	  while (!$result->EOF){
	   
	    array_push($_blacklisted_pins,$result->fields['pid']);
		  
		  $result->moveNext();
      }
      return $_blacklisted_pins;
    }

	/**
	 * Add PINs to database to indicate they have been emitted
	 */
	public function addPins($_pins = array()){
		
		if(isset($_pins)) {
			
			$db = $this->registry->db;
	  
	  		$conn = $db->getInstance();
			
			// enable bulk binding to perform batch inserts of the pins
			$conn->bulkBind = true;
		
			// for bulk binding to work we need to create a multidimensional array from our pins array
			$_array = array();
			foreach ($_pins as $pin) {
				array_push($_array, array($pin));
			}

      		$sql_select = "INSERT INTO pingen.pingen_pin (pid) VALUES (?)";
	  		
      		$conn->Execute($sql_select, $_array);
			
			$conn->bulkBind = false;
		} 
		return true;
	}

	/**
	 * @quanity string the number of pins to generate
	 */
	public function generatePin($quantity){
		
		$_generated_pins = array();
		
		$x = 0; // iterator use in for loop
		
		// Create an initial array of all possible pin values	
		$this->_pin_range = $this->generatePinRange();
		
		// Create an array of which pin numbers have already been allocated
		$this->_allocated_pins = $this->getAllocatedPins();

		// Create an array of pin numbers blacklisted
		$this->_blacklisted_pins = $this->getBlacklistedPins();
		
		// Merge our allocated and blacklisted arrays into 1 'bad' array
		$_bad_pins_list = array_merge($this->_allocated_pins, $this->_blacklisted_pins);
		
		//sort($_bad_array_list);
		
		// Take the pin range and examine the values that do not appear in the 'bad' array
		$_free_pin_range = array_diff($this->_pin_range, $_bad_pins_list);

		$free_range_pin_count = count($_free_pin_range);
		
		$max_random_counter = $free_range_pin_count - 1;
		
		// generate our random pins and push them into a $_generated_pins array
		for ($x = 1; $x <= $quantity; $x++){
			array_push($_generated_pins, $_free_pin_range[rand(0,$max_random_counter)]);
		}
		
		if ($this->addPins($_generated_pins)) { // Add these pin numbers to the db to give an indication that they have been 'emitted'
				return $_generated_pins;	
			} else {
				return false;
			}
		
	}

} /*** end of class ***/
?>
