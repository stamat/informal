<?php
  include 'Field.php';

	$data_structure = array(
        new Field('image', 'Upload Image', true, 'image'),
		new Field('name', 'Name', true),
		new Field('insta_handle', 'Instagram Handle', true),
		new Field('email', 'Email Address', true, 'email'),
		new Field('lens', 'Lens Used', true, 'text', true)
	);
