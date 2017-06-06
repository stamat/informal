<?php
	class Field {
		public $name;
		public $label;
		public $type; //text, select, image, number, checkbox, radio, select-multiple, checkbox-multiple, password, email... for HTML generation
		public $defaut; //default value
		public $placeholder;
		public $options; //options if it's select
		public $datatype = 'varchar'; //sql datatype
		public $length; //contraint of length
		public $required;
		public $sql;

		public function buildSQL() {
			$sql = $this->name. '   '. $this->datatype.'('.$this->length.')   ';

			if ($this->default !== null) {
				$sql . 'NOT NULL';
			}

			return $sql;
		}

		public function __construct($name, $label, $required = false, $type = 'text', $default = null, $placeholder = '', $length = 512, $options = null, $datatype = 'varchar') {
		    $this->name = $name;
			$this->label = $label;
			$this->required = $required;
			$this->type = $type;
			$this->default = $default;
			$this->placeholder = $placeholder;
			$this->option = $options;
			$this->datatype = $datatype;
			$this->length = $length;
			$this->sql = $this->buildSQL();

		}
	}
