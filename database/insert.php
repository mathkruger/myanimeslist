<?php
include('../controllers/AnimeController.php');

$controller = new AnimeController();
$obj = new Anime(0, 'Fullmetal', 'Awesome anime', 'http://www.thegeekedgods.com/wp-content/uploads/2017/02/FMA-Cover.jpg');

echo $controller->create($obj);