<?php

spl_autoload_register(function ($class) {
	if($class == "Element") {
		require "abstract/".$class.".php";
	}
	else {
		require "class/".$class.".php";
	}
});

class QForm {
	
	private $ignoreValidation; //Should the form be valid HTML?
	private $validationMessages = array(); //Array of validation messages
	
	private $formHtml;
	private $innerHtml;
	private $formMethod;
	private $formAction;
	private $formName;
	private $formElements = array();
	
	function __construct($method, $action, $name = null, $ignoreValidation = false) {
		$this->formMethod = $method;
		$this->formAction = $action;
		$this->formName = $name;
		$this->ignoreValidation = $ignoreValidation;
	}
	
	/**
	 * Adds elements to the form
	 */
	private function addElements() {
		if(!empty($this->formElements)) {
			foreach($this->formElements as $element) {
				$this->innerHtml .= $element;
			}
		}
	}
	
	//Check to see what attributes are used/set
	protected function checkAttributes(&$value, &$name, &$id, $type) {
		
		//TODO add support for textarea cols/rows
		if(isset($value) && $type != "textarea") {
			$value = " value='$value'";
		}
		if(isset($name)) {
			$name = " name='$name'";
		}
		if(isset($id)) {
			if(preg_match('/\s/', $id) && self::$ignoreValidation === false) {
				
				//If an ID contains a space, add a message into the array
				self::$validationMessages[] = "An ID must not contain spaces to validate properly";
			}
			$id = " id='$id'";
		}
	}
	
	/**
	 * Get an element's HTML to be used when generating the form
	 * @param Element $element
	 * @return string
	 */
	protected function getHtml($element) {
		return $element->html;
	}
	
	/**
	 * Add a label to go along with a field if one is set
	 * @param string $label
	 * @param string $field
	 * @return string
	 */
	protected function checkLabel($label, $field) {
		return (isset($label)) ? "<label>$label</label> $field" : $field;
	}
	
	/**
	 * Outputs the form
	 */
	public function output() {
		//If the instance is not set to ignore validation and the message array is not empty, report the validation issue
		if($this->ignoreValidation === false && !empty($this->validationMessages)) {
			foreach($this->validationMessages as $message) {
				$this->formHtml .= $message."<br />";
			}
		}
		else {
			$this->addElements();
			$this->formHtml = "
				<form method='$this->formMethod' name='$this->formName' action='$this->formAction'>
					<fieldset>
						$this->innerHtml
					</fieldset>
				</form>";
		}
		echo $this->formHtml;
	}
	
	/**
	 * Add the element's HTML that was created to the array of form elements. Used internally for building the form
	 * @param Element $element
	 * @throws Exception if the argument is not of the type 'Element'
	 */
	public function addElement($element) {
		if($element instanceof Element) {
			$this->formElements[] = $this->getHtml($element);
		}
		else {
			throw new Exception("Unsupported element added to form");
		}
	}
	
	/**
	 * Generates a new line in HTML and adds it to the array of form elements
	 */
	public function newLine() {
		$this->formElements[] = "<br />";
	}
	
}

?>
