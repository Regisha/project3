<?php
	header("Content-Type:text/html;charset=utf-8");
	setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251');
	$time_start = microtime(true);
	error_reporting(E_ALL);

	define('ABSOLUTE__PATH__',$_SERVER['DOCUMENT_ROOT']);
	include_once(ABSOLUTE__PATH__.'/programm_files/mysql.php');
	session_start();
	
	if (isset($_SESSION['__ID__']))
	{
	die ("<meta http-equiv=refresh content='0; url=rupka.php?page=zakaz'>");
	}
	
	if(isset($_POST['Submit_login']))
	{
	$pass = md5($_POST['pasw']);
				if ($pass !== '0d95caa4106323a5159fb88418b7e192')
				{
				?>
				<div class="alert alert-dismissible alert-danger">
					<h3 class="text-center text-danger">������</h3>
					<strong>��������� ������ �� �����.</strong><br /> 
				
					<p class="text-center">
						<input type="button" value=" ��������� " onclick="location.href = 'http://<?php echo $_SERVER['HTTP_HOST']; ?>/kpp.php';return false;" class="btn btn-primary btn-sm"/>
					</p>
				</div>
				<div class="clearfix"></div>
				<?php
				$_SESSION['spam'] = isset($_SESSION['spam']) ? $_SESSION['spam'] + 1 : 1;
				$_SESSION['antispam'] = true;
				exit();
				}
			$_SESSION['__ID__'] = 1;
			die ("<meta http-equiv=refresh content='0; url=rupka.php?page=zakaz'>");
	}
	
	if 	(isset($_GET['login']))
	{
	echo "
	<div class='alert alert-dismissible alert-warning text-center'>
		<strong>�� �� ���������������, ���������� ���������������.</strong>
	</div>";
	}

	if 	(
		(isset($_SESSION['spam'])) &&
		($_SESSION['spam'] >= 3) and
		(!isset($_SESSION['mktime']))
		)
	{
	$_SESSION['mktime'] = time() + (3600 * rand(1,5));
	}


?>
<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta charset="utf-8">
	<!-- Style CSS -->
	<link href="css/cerulean_bootstrap.min.css" media="screen" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>
<body>

<div class="container">
	<div class="row">
	<?php
	
	if 	(
		(isset($_SESSION['mktime'])) &&
		($_SESSION['mktime'] > time())
		)
	{
	echo "
	<div class='alert alert-dismissible alert-danger text-center'>
		<strong>�� ��������� ��� ������� ������ ���������� ����� � ������.<br/> �� �������������� �� ".date("H:i",$_SESSION['mktime'])." ".date("d-m-Y",$_SESSION['mktime']).".</strong><br />
		<a class='btn btn-primary' href='http://".$_SERVER['HTTP_HOST']."'>&larr; �� ����.</a>
	</div>";
	define('__PANEL__LOGIN__', 1 );
	}
	
	if (!defined('__PANEL__LOGIN__'))
	{
	?>
	<div class="col-md-8">
		
		<div class="col-md-6 col-md-offset-3">
			<h2 class="text-center page-header"><center><img class="img-responsive hidden-xs hidden-sm" src="2.png" alt="NEXT CRM" /></center>CRM �����������</h2>
		</div>
		
		<div class="clearfix"></div>
		
		<form class="form-horizontal" method="post" name="passform">
		<fieldset>
			<legend>��������� ����������� ������</legend>
			
			<div class="form-group">
				<label for="inputlogin" class="col-lg-2 control-label">�����</label>
				<div class="col-lg-10">
					<input name="login" class="form-control" id="inputlogin" placeholder="login" type="text">
				</div>
			</div>
			
			<div class="form-group">
				<label for="inputPassword" class="col-lg-2 control-label">Password</label>
				<div class="col-lg-10">
					<input name="pasw" class="form-control" id="inputPassword" placeholder="Password" type="password">
				</div>
			</div>

			<div class="form-group text-center">
			<div class="col-lg-10 col-lg-offset-2">
				<button name="Submit_login" type="submit" class="btn btn-primary">����</button>
			</div>
			</div>
		</fieldset>
		</form>		
	</div>
	<?php
	}
	?>
	</div>
</div>

</body>
</html>