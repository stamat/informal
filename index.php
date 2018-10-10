<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="sr"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="sr"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="sr"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="sr" itemscope itemtype="http://schema.org/Article"> <!--<![endif]-->
<?php include 'fields.php'; ?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Image submission form</title>

	<link rel="stylesheet" href="https://use.typekit.net/qpq4oxa.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="stylesheet" href="css/main.scss.css">

	<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>


	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
	<script>
		window.FIELDS = [
			<?php
			$names = array();
			foreach ($data_structure as $field):
				array_push($names, json_encode($field));
			endforeach;
			echo implode(', ', $names);
			?>
		];
	</script>
	<script type="text/javascript" src="js/script.js"></script>
</head>
<body>
	<div class="content clearfix">
		<div class="submit-panel-inner">
            <div class="inner-wrap">
                <div class="informal" data-action="process.php" data-method="post">
					<?php foreach ($data_structure as $field):
						include 'templates/'.$field->type.'.php';
					endforeach; ?>

                    <div class="checkbox">
                        <input id="terms" name="terms" type="hidden">
                        <i></i>I have read and agree with the Terms &amp; Conditions.
                    </div>

					<div id="gen-err" class="err"></div>
					<div id="suc" class="suc">Your photo has been submitted!</div>

					<div class="main-loader-wrap">
						<img class="loader main-loader" src="css/ajax-loader.gif" />
					</div>

                    <input type="submit" class="button red" value="Submit Photo">

                </div>
            </div>
        </div>
	</div>
</body>
</html>
