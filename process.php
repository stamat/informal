<?php
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

	function generateRequiredMap($data_structure) {
		$required = array();

		$len = count($data_structure);
		for ($i = 0; $i < $len; $i++) {
			if($data_structure[$i]->required) {
				array_push($required, $data_structure[$i]);
			}
		}

		return $required;
	}

	$required = generateRequiredMap($data_structure);

	//TODO: there is no email, except the paypal emai....
	//TODO: there is no name field for the educator as well
	//TODO: there is no limitation to the number of images

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

	try {

		$db_username = 'homestead';
		$db_password = 'secret';

		$db = new PDO(
		   'mysql:host=localhost;dbname=informal;charset=utf8',
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

	try {
		$tablesql = createTableSQL($table_name, $data_structure);
		$db->exec( $tablesql );
	} catch (PDOException $e) {
		echo '{"error":"'.$e->getMessage().'"}';
	}


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

		$s = buildInsertSQL($table_name, $_POST, $data_structure);

		$stmt = $db->prepare( $s['sql'] );
		$stmt->execute( $s['values'] );
		if( !$stmt->rowCount() ) {
			echo '{"error":"Error writting to the database, please try again"}';
			exit();
		}

		function buildMessage($post, $data_structure) {
			$msg = "";

			$len = count($data_structure);
			for ($i = 0; $i < $len; $i++) {
				if(isset($_POST[$data_structure[$i]->name]) && trim($_POST[$data_structure[$i]->name]) !== "") {
					$msg .= $data_structure[$i]->label . ": " .$_POST[$data_structure[$i]->name]. "\n";
				}
			}

			return $msg;
		}

		$msg = buildMessage($_POST, $data_structure);

		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . "\r\n";
		$headers .= "From: SIPE 2015 <no-reply@example.com>\r\n";
		$headers .= "Reply-To: SIPE 2015 <no-reply@example.com>\r\n";
		$headers .=	"Return-Path: SIPE 2015 <no-reply@example.com>\r\n";
		$headers .= "X-Mailer: PHP/".phpversion()."\r\n";
		$headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'];
		// send email
		//mail("stamatmail@gmail.com","Example Form: ".$_POST['class_name'], $msg, $headers);

		echo '{"success": true}';
?>
