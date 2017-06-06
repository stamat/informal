<?php
  include 'Field.php';

	$data_structure = array(
		new Field('class_name', 'Class Name', true),
		new Field('class_description', 'Class Description', true),
		new Field('class_type', 'Class Type', false),
		new Field('demo', 'Demo or Hands-on', false),
		new Field('class_location', 'Class Location', false),
		new Field('address1', 'Address', false),
		new Field('address2', 'Address line 2', false),
		new Field('city', 'City', false),
		new Field('state', 'State', false),
		new Field('zip', 'Zip', false),
		new Field('price', 'Price', false),
		new Field('start_time', 'Start time', false),
		new Field('end_time', 'End time', false),
		new Field('facebook', 'Facebook', false),
		new Field('instagram', 'Instagram', false),
		new Field('twitter', 'Twitter', false),
		new Field('seats', 'Seats available', false),
		new Field('bio', 'Instructor bio', false),
		new Field('paypal', 'Paypal email', false),
		new Field('hero_image', 'Hero Image', false, 'image'),
		new Field('image1', 'Image 1', false, 'image'),
		new Field('image2', 'Image 2', false, 'image'),
		new Field('image3', 'Image 3', false, 'image'),
		new Field('image4', 'Image 4', false, 'image'),
		new Field('image5', 'Image 5', false, 'image')
	);
