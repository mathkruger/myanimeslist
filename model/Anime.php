<?php
include './model/ConnectionDB.php';

class Anime {
	public $id;
	public $title;
	public $description;
	public $coverLink;

	public function __construct($title, $description, $coverLink) {
		$this->title = $title;
		$this->description = $description;
		$this->coverLink = $coverLink;
	}

	public function setId($id) {
		$this->id = $id;
	}
}