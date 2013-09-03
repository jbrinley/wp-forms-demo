<?php

/**
 * Class WP_FormsDemo_Decorator_TableCells
 *
 * Wrap a form element in table cells (the first column will be empty)
 */
class WP_FormsDemo_Decorator_TableCells extends WP_Form_Decorator {
	public function render( WP_Form_Component $element ) {
		return '<td></td><td>'.$this->component_view->render($element).'</td>';
	}
}
