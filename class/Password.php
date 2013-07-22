<?php

class Password extends Element {
	
	//Checks the attributes, generates a label, and creates the element
	protected function constructElement() {
		$this->checkAttributes($this->value,$this->name, $this->id, "password");
		$this->html .= $this->checkLabel($this->label, "<input type='password'$this->name$this->id$this->value />");
	}
	
}

?>