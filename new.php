<?php
	session_start();
	require_once('core/config.php');

	if (isset($_GET['clean']))
		$ez->clearCache();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		include('./controllers/AnimeController.php');

	    $controller = new AnimeController();
	    $anime = new Anime($_POST['title'], $_POST['description'], $_POST['cover']);

	    $result = $controller->create($anime);
	    if($result > 0) {
	    	header('Location: new.php?success=true');
	    }
	    else {
	    	header('Location: new.php?error=true');	
	    }
	}

	$ez->title('New');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $ez->render('header'); ?>
	</head>

	<body>
		<?php
			$ez->render('navigation');
			$ez->render('anime_create');
			$ez->render('footer');
		?>
	</body>
</html>