<?php
include('./model/Anime.php');

class AnimeController {
	private $connection;

	public function __construct() {
		$config = include('./database/config_database.php');

		$this->connection = ConnectionDB::connect($config['host'], $config['database'], $config['username'], $config['password']);
	}

	public function getAll() {
		try {
			$stmt = $this->connection->prepare('SELECT id, title, description, cover_link FROM animes');
			$stmt->execute();

			return $stmt->fetchAll();
		}
		catch(PDOException $e) {
			echo $e;
		}
	}

	public function get($id) {
		try {
			$stmt = $this->connection->prepare('SELECT id, title, description, cover_link FROM animes where id = :id');
			$stmt->execute(array(':id' => $id));

			return $stmt->fetch();
		}
		catch(PDOException $e) {
			echo $e;
		}
	}

	public function create($anime) {
		try {
			$stmt = $this->connection->prepare('INSERT INTO animes (title, description, cover_link) VALUES (:title,:description,:link);');
			$stmt->execute(array(
				':title' => $anime->title,
				':description' => $anime->description,
				':link' => $anime->coverLink
			));

			return $stmt->rowCount();
		}
		catch(PDOException $e) {
			echo $e;
		}
	}

	public function edit($anime) {
		try {
			$stmt = $this->connection->prepare('UPDATE animes SET title = :title, description = :description, cover_link = :link WHERE id = :id');
			$stmt->execute(array(
				':title' => $anime->title,
				':description' => $anime->description,
				':link' => $anime->coverLink,
				':id'   => $anime->id,
			));

			return $stmt->rowCount();
		}
		catch(PDOException $e) {
			echo $e;
		}
	}

	public function delete($anime) {
		try {
			$stmt = $this->connection->prepare('DELETE FROM animes WHERE id = :id');
			$stmt->execute(array(
				':id'   => $anime->id,
			));

			return $stmt->rowCount();
		}
		catch(PDOException $e) {
			echo $e;
		}
	}
}