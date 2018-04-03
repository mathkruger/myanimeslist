<?php
	session_start();
	require_once('core/config.php');

	include('./controllers/AnimeController.php');
	$controller = new AnimeController();

	if (isset($_GET['clean']))
		$ez->clearCache();

	if(isset($_GET['id'])) {
		$ez->assign('anime', $controller->get($_GET['id']));

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		    $anime = new Anime($_POST['title'], $_POST['description'], $_POST['cover']);
			$anime->setId($_GET['id']);

		    $result = $controller->edit($anime);
		    echo $result;
		    if($result > 0) {
		    	header('Location: index.php?success=true');
		    }
		    else {
		    	header('Location: index.php?error=true');	
		    }
		}
	}
	else {
		header('Location: new.php');
	}


	$ez->title('Edit');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $ez->render('header'); ?>
	</head>

	<body>
		<?php
			$ez->render('navigation');
			$ez->render('anime_edit');
			$ez->render('footer');
		?>
	</body>
</html>