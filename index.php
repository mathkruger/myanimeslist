<?php
	session_start();
	require_once('core/config.php');

	if (isset($_GET['clean']))
		$ez->clearCache();

    include('./controllers/AnimeController.php');

    $controller = new AnimeController();
    $ez->assign('results', $controller->getAll());

    $ez->title('Home');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $ez->render('header'); ?>
	</head>

	<body>
		<?php
			$ez->render('navigation');
			$ez->render('anime_list');
			$ez->render('footer');
		?>
	</body>
</html>