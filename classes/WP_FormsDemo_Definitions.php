<?php

/**
 * Class WP_FormsDemo_Definitions
 */
class WP_FormsDemo_Definitions {
	/**
	 * The generic callback for building forms
	 *
	 * @param WP_Form $form
	 * @return void
	 */
	public function build_form( WP_Form $form ) {
		$form->add_class('wp-form');
		$args = func_get_args();
		switch ( $form->id ) {
			case 'kitchen-sink':
				call_user_func_array(array($this, 'kitchen_sink'), $args);
				break;
			case 'kitchen-table':
				call_user_func_array(array($this, 'kitchen_table'), $args);
				break;
			case 'validation':
				call_user_func_array(array($this, 'validation'), $args);
		}
	}

	/**
	 * Create a form that includes every type of element
	 *
	 * @param WP_Form $form
	 * @return void
	 */
	private function kitchen_sink( WP_Form $form ) {
		$text_inputs = WP_Form_Element::create('fieldset')->set_name('text_inputs')->set_label('Text Inputs');
		$buttons = WP_Form_Element::create('fieldset')->set_name('buttons')->set_label('Buttons');
		$checkboxes = WP_Form_Element::create('fieldset')->set_name('checkboxes')->set_label('Checkboxes');
		$radios = WP_Form_Element::create('fieldset')->set_name('radios')->set_label('Radio Buttons');
		$menus = WP_Form_Element::create('fieldset')->set_name('menus')->set_label('Menus');
		$files = WP_Form_Element::create('fieldset')->set_name('files')->set_label('Files');
		$hidden = WP_Form_Element::create('fieldset')->set_name('hidden')->set_label('Hidden Fields');

		$form
			->add_element($text_inputs)
			->add_element($checkboxes)
			->add_element($radios)
			->add_element($menus)
			->add_element($files)
			->add_element($hidden)
			->add_element($buttons);

		$text_field = WP_Form_Element::create('text')->set_name('a_text_field')->set_label('Single Line Text')->set_description('A basic text input field');
		$textarea = WP_Form_Element::create('textarea')->set_name('a_textarea')->set_label('Multi Line Text')->set_description('A textarea');
		$text_inputs->add_element($text_field)->add_element($textarea);

		$checkbox_group = WP_Form_Element::create('checkboxes')->set_name('checkbox_group')->set_label('Group of checkboxes')->set_description('These checkboxes are grouped with a common name');
		$checkbox_group
			->add_option(1, 'Option 1')
			->add_option(2, 'Option 2')
			->add_option(3, 'Option 3');
		$single_checkbox = WP_Form_Element::create('checkbox')->set_name('single_checkbox')->set_label('Single Checkbox')->set_id('single_checkbox')->set_description('A checkbox that is not part of any group');
		$checkboxes->add_element($checkbox_group)->add_element($single_checkbox);

		$radio_group = WP_Form_Element::create('radios')->set_name('radio_group')->set_label('Radio Button Group')->set_description('These radio buttons share a common name');
		$radio_group
			->add_option('A', 'Option A')
			->add_option('B', 'Option B')
			->add_option('C', 'Option C');
		$single_radio = WP_Form_Element::create('radio')->set_name('single_radio')->set_label('Single Radio Button')->set_id('single_radio')->set_description('Do you really want to use this? Probably not.');
		$radios->add_element($radio_group)->add_element($single_radio);

		$select_drop_down = WP_Form_Element::create('select')->set_name('single_select')->set_label('Select Drop-Down')->set_description('Your run-of-the-mill drop-down box');
		$select_drop_down
			->add_option(1, 'First')
			->add_option(2, 'Second')
			->add_option(3, 'Third');

		$multiselect = WP_Form_Element::create('select')->set_name('multi_select')->set_label('Multiple Select Box')->set_description('You can pick more than one')->set_attribute('multiple', 'multiple');
		$multiselect
			->add_option(4, 'Fourth')
			->add_option(5, 'Fifth')
			->add_option(6, 'Sixth');
		$menus->add_element($select_drop_down)->add_element($multiselect);

		$file_upload = WP_Form_Element::create('file')->set_name('file_upload')->set_label('Upload a File')->set_description('A simple file upload control');
		$files->add_element($file_upload);

		$hidden_field = WP_Form_Element::create('hidden')->set_name('hidden_field')->set_value("You can't see me");
		$hidden->add_element($hidden_field);

		$submit = WP_Form_Element::create('submit')->set_name('submit')->set_value('Submit Button (Disabled)')->set_attribute('disabled', 'disabled');
		$reset = WP_Form_Element::create('reset')->set_name('reset')->set_value('Reset Button');
		$button = WP_Form_Element::create('button')->set_name('button')->set_value('Button Button');
		$buttons->add_element($submit)->add_element($reset)->add_element($button);

	}

	/**
	 * Register the Table Layout before delegating to the kitchen_sink form definition
	 *
	 * @param WP_Form $form
	 * @return void
	 */
	private function kitchen_table( WP_Form $form ) {
		$layout = new WP_FormsDemo_TableLayout();
		$layout->add_hooks();
		$this->kitchen_sink($form);
	}

	/**
	 * A simple form with a view fields and a validation callback
	 *
	 * @param WP_Form $form
	 * @return void
	 */
	private function validation( WP_Form $form ) {
		$form
			->add_element( WP_Form_Element::create('text')->set_name('username')->set_label(__('Username')) )
			->add_element( WP_Form_Element::create('text')->set_name('email')->set_label('Email Address') )
			->add_element( WP_Form_Element::create('password')->set_name('password')->set_label('Password') )
			->add_element( WP_Form_Element::create('submit')->set_name('submit') )
			->add_validator( array( $this, 'validation_form_validator' ) )
		;
	}

	/**
	 * The validation callback for the validation example. This form will never
	 * successfully validate.
	 *
	 * @see WP_FormsDemo_Definitions::validation
	 * @param WP_Form_Submission $submission
	 * @param WP_Form $form
	 * @return void
	 */
	public function validation_form_validator( WP_Form_Submission $submission, WP_Form $form ) {
		// In a real life scenario, you would probably check against a database or some such
		$submission->add_error( 'username', __('Your username is already taken.') );

		// Find a reason to reject the email address
		$dot_position = strrpos($submission->get_value('email'), '.', -1);
		if ( $dot_position === FALSE ) {
			$submission->add_error( 'email', __('Invalid email address.') );
		} else {
			$submission->add_error( 'email', sprintf(__('Please do not use a "%s" domain.'), substr($submission->get_value('email'), $dot_position)));
		}

		// There is no way to meet all the password criteria
		if ( strlen($submission->get_value('password')) > 8 ) {
			$submission->add_error( 'password', __('Passwords must contain 8 or fewer characters') );
		} else {
			$submission->add_error( 'password', __('Passwords must contain at least two uppercase letters, two lowercarse letters, two numbers, and three special characters.'));
		}
	}
}
