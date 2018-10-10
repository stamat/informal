<?php
	error_reporting(E_ERROR | E_PARSE);

	session_start();

  	include 'fields.php';
	$table_name = 'informal';

	function createTableSQL($name, $structure) {
		$pre = 'CREATE TABLE IF NOT EXISTS '.$name. ' ( id MEDIUMINT NOT NULL AUTO_INCREMENT, ';
		$fields = array();
		$end = ', PRIMARY KEY (id));';

		$len = count($structure);
		for ($i = 0; $i < $len; $i++) {
			array_push($fields, $structure[$i]->sql);
		}

		return $pre . implode(', ', $fields) . $end;
	}

	function generateMap($data_structure, $field, $val = true) {
		$required = array();

		$len = count($data_structure);
		for ($i = 0; $i < $len; $i++) {
			if($data_structure[$i]->$field === $val) {
				array_push($required, $data_structure[$i]);
			}
		}

		return $required;
	}

	$required = generateMap($data_structure, 'required');
	$image_fields = generateMap($data_structure, 'type', 'image');

	function moveImages($post, $image_fields) {
		$ds = DIRECTORY_SEPARATOR;
		$len = count($image_fields);
		for ($i = 0; $i < $len; $i++) {
			if(isset($post[$image_fields[$i]->name]) && trim($post[$image_fields[$i]->name]) !== "") {
				try {
			        rename(dirname( __FILE__ ) . $ds."uploads". $ds. $post[$image_fields[$i]->name], dirname( __FILE__ ). $ds."images". $ds.$post[$image_fields[$i]->name]);
			    } catch (Exception $e) {
			        echo '{"error":"'.$e->getMessage().'"}';
			        exit();
			    }

			}
		}
	}

	// VALIDATION
	$len = count($required);
	$require_errors = array();
	for ($i = 0; $i < $len; $i++) {
		if(!isset($_POST[$required[$i]->name]) || trim($_POST[$required[$i]->name]) === "") {
			array_push($require_errors, '{"message":"'.$required[$i]->label.' is a mandatory field", "field": "'.$required[$i]->name.'"}');

		}
	}

	if (count($require_errors)) {
		echo '{"error": ['.implode(',', $require_errors). '], "type": "validation"}';
		exit();
	}

	// DB Connect
	try {
		$db_username = 'root';
		$db_password = 'root';
		$db_host = 'localhost';
		$db_port = 8889;

		$db = new PDO(
		   'mysql:host='.$db_host.';port='.$db_port.';dbname=informal;charset=utf8',
		   $db_username,
		   $db_password
		);

		$db_username = null;
		$db_password = null;

	} catch (Exception $e) {
		echo '{"error":"Error connecting to the database, please try again"}';
		exit();
	}

	// ERROR MODE EXCEPTION
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// STATEMENT EMULATION OFF
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	//AUTO DB Table creation
	try {
		$tablesql = createTableSQL($table_name, $data_structure);
		$db->exec( $tablesql );
	} catch (PDOException $e) {
		echo '{"error":"'.$e->getMessage().'"}';
	}

	moveImages($_POST, $image_fields);


	function buildInsertSQL($table_name, $post, $data_structure) {
		$pre = 'INSERT INTO '.$table_name.'(';
		$mid = ') VALUES (';
		$end = ')';
		$cols = array();
		$entr = array();
		$vals = array();

		$len = count($data_structure);
		for ($i = 0; $i < $len; $i++) {
			if(isset($_POST[$data_structure[$i]->name]) && trim($_POST[$data_structure[$i]->name]) !== "") {
				array_push($cols, $data_structure[$i]->name);
				array_push($entr, '?');
				array_push($vals, $_POST[$data_structure[$i]->name]);
			}
		}

		$sql = $pre . implode(',', $cols) . $mid . implode(',', $entr) . $end;

		return array('sql' => $sql, 'values' => $vals);
	}


	// Build and execute insert SQL
	$s = buildInsertSQL($table_name, $_POST, $data_structure);

	$stmt = $db->prepare( $s['sql'] );
	$stmt->execute( $s['values'] );
	if( !$stmt->rowCount() ) {
		echo '{"error":"Error writting to the database, please try again"}';
		exit();
	}

	function buildMessage($data_structure, $host) {
		$msg = "";

		$len = count($data_structure);
		for ($i = 0; $i < $len; $i++) {
			if(isset($_POST[$data_structure[$i]->name]) && trim($_POST[$data_structure[$i]->name]) !== "") {
				if ($data_structure[$i]->type === 'image') {
					$msg .= $data_structure[$i]->label . ": ". $host .$_POST[$data_structure[$i]->name]. "\n";
				} else {
					$msg .= $data_structure[$i]->label . ": " .$_POST[$data_structure[$i]->name]. "\n";
				}
			}
		}

		return $msg;
	}

	function sendMail($to, $subject, $host) {
		$msg = buildMessage($data_structure, $host);

		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . "\r\n";
		$headers .= "From: Informal <no-reply@informal.com>\r\n";
		$headers .=	"Return-Path: Informal <office@informal.com>\r\n";
		$headers .= "X-Mailer: PHP/".phpversion()."\r\n";
		$headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'];
		// send email
		mail($to, $subject, $msg, $headers);
	}

	//sendMail("some@email.com", "Informal: "+ $_POST['field'])

	echo '{"success": true}';
