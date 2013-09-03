<?php

/**
 * Class WP_FormsDemo_Decorator_TableLabel
 *
 * The idea here is to put the label in one table cell, the rest of the form
 * element in another.
 */
class WP_FormsDemo_Decorator_TableLabel extends WP_Form_Decorator_Label {
	public function render( WP_Form_Component $element ) {
		$label = '';
		if ( is_callable(array($element, 'get_label')) ) {
			$label = $element->get_label();
		}
		if ( empty($label) ) {
			return '<td></td><td>'.$this->component_view->render($element).'</td>';
		}

		$position = self::POSITION_BEFORE;
		if ( !empty($this->args['position']) ) {
			$position = $this->args['position'];
		}
		$class = apply_filters('wp_form_label_html_class', 'form-label');
		switch ( $position ) {
			case self::POSITION_AFTER:
				$template = '<td>%4$s</td><td><label for="%1$s" class="%2$s">%3$s</label></td>';
				break;
			case self::POSITION_SURROUND:
				$template = '<label for="%1$s" class="%2$s">%4$s %3$s</label>';
				break;
			case self::POSITION_BEFORE:
			default:
				$template = '<td><label for="%1$s" class="%2$s">%3$s</label></td><td>%4$s</td>';
				break;
		}
		return sprintf($template, $element->get_id(), $class, $label, $this->component_view->render($element));
	}
}
