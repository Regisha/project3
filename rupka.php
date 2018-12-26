<?php
	header("Content-Type:text/html;charset=utf-8");
	ini_set('date.timezone', 'Asia/Yekaterinburg');
	setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251');
	$time_start = microtime(true);
	$orig_memory = (function_exists('memory_get_usage')?memory_get_usage():0);
	error_reporting(E_ALL);

	define('ABSOLUTE__PATH__',$_SERVER['DOCUMENT_ROOT']);
	session_start();

	$time_start = microtime(true);
	$stat_zayavki = array(1 => 'Алма-Ата', 2 => 'В Москве', 3 => 'В другом регионе', 4 => 'Совсем в другом' );	
	if (isset($_GET['exit']))
	{
	session_destroy();
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/kpp.php?login');
	exit;
	}

	if (!isset($_SESSION['__ID__']))
	{
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/kpp.php?login');
		exit;
	}

	define( '__PANEL__BOARD__', 1 );
	
	include_once(ABSOLUTE__PATH__.'/programm_files/mysql.php');
	include_once(ABSOLUTE__PATH__.'/programm_files/functions.php');
	
		if (isset($_SESSION['kurs']))
		{
			if ((time() - $_SESSION['kurs']['mktime'] > 3600 ))
			{
			$get_kurs = send_request('https://www.cbr-xml-daily.ru/daily_json.js');
			$kurs = json_decode($get_kurs,true);
			$_SESSION['kurs'] = array ('mktime' => time(), 'kurs' => $kurs);
			}
		}
		else
			{
			$get_kurs = send_request('https://www.cbr-xml-daily.ru/daily_json.js');
			$kurs = json_decode($get_kurs,true);
			$_SESSION['kurs'] = array ('mktime' => time(), 'kurs' => $kurs);
			}

	$user = array();

	$SQL = "SELECT id,login,pass,name,status FROM GRUZ_worker";
	$B_user = mysqli_query($mysql,$SQL);
	if(!$B_user) exit(mysqli_error($mysql));
	while($usr=mysqli_fetch_assoc($B_user))
	{
	$user[$usr['id']] = $usr;
	}			
	mysqli_free_result($B_user);
	
	$super_admin = array(1,2);
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
	<title>KARGO TRANS</title>
	<!-- Style CSS -->
	<link href="css/cerulean_bootstrap.min.css" media="screen" rel="stylesheet">
	<link href="js/Easy-Searchable-Filterable-Select-List-with-jQuery/jquery.searchableSelect.css" media="screen" rel="stylesheet">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
 
	
	<style>
	.hover:hover {
	background:#F6D9A3;
	}
    .m5 {margin-top:2px; margin-bottom:2px;}
    .m4 {padding-left:2px; padding-right:2px;}	
    .mtop {margin-top:5px;padding-top:5px;}
    .hr {border:1px solid #2FA4E7;}
	table td a.atd{width:100%;height:100%;display:inline-block;color:white;}
	a.atd:hover {color:black;}
table {
  overflow: hidden;
}

td, th {
  padding: 10px;
  position: relative;
  outline: 0;
}

body:not(.nohover) tbody tr:hover {
  background-color: #F9F9F9;
}

td:hover::after,
thead th:not(:empty):hover::after,
td:focus::after,
thead th:not(:empty):focus::after { 
  content: '';  
  height: 10000px;
  left: 0;
  position: absolute;  
  top: -5000px;
  width: 100%;
  z-index: -1;
}

td:hover::after,
th:hover::after {
  background-color: #F9F9F9;
}

td:focus::after,
th:focus::after {
  background-color: lightblue;
}

/* Focus stuff for mobile */
td:focus::before,
tbody th:focus::before {
  background-color: lightblue;
  content: '';  
  height: 100%;
  top: 0;
  left: -5000px;
  position: absolute;  
  width: 10000px;
  z-index: -1;
}
textarea.form-control {
    min-height: 30px;
}
.horizontal {
  overflow: auto;
  max-height: 150px;
}
.panel {
    margin-bottom: 2px;
}
	</style>
</head>
<body>
	<div class="container">
	<div class="row">
		<div class="col-md-2">
			<div class="row text-center"> 
				<h2 class="page-header"><img class="img-responsive hidden-xs hidden-sm" src="2.png" alt="Code CMS" /><br> </h2>
				<p><?php echo privet().'<br>'. $user[$_SESSION['__ID__']]['name']; ?></p>
				<h5>Курсы валют</h5>
				<p><?php echo isset($_SESSION['kurs']['kurs']['Valute']['USD'])?$_SESSION['kurs']['kurs']['Valute']['USD']['CharCode'].' - '.$_SESSION['kurs']['kurs']['Valute']['USD']['Value']:'USD ?'; ?></p>
				<p><?php echo isset($_SESSION['kurs']['kurs']['Valute']['EUR'])?$_SESSION['kurs']['kurs']['Valute']['EUR']['CharCode'].' - '.$_SESSION['kurs']['kurs']['Valute']['EUR']['Value']:'EUR ?'; ?></p>
			</div>
			<div class="row">
				<div class="list-group">
					<a href="?page=main" class="list-group-item <?php echo $_GET['page'] == 'main'?'active':''; ?>">
						<span class="glyphicon glyphicon-home"></span>
						Главная
					</a>
					<a href="?page=client" class="list-group-item <?php echo $_GET['page'] == 'client'?'active':''; ?>">
						<span class="glyphicon glyphicon-user"></span>
						Клиенты
					</a>
					<a href="?page=otpravka" class="list-group-item <?php echo $_GET['page'] == 'otpravka'?'active':''; ?>">
						<span class="glyphicon glyphicon-refresh"></span>
						Отправка груза
					</a>
					<a href="?page=categor_gruza" class="list-group-item <?php echo $_GET['page'] == 'categor_gruza'?'active':''; ?>">
						<span class="glyphicon glyphicon-th"></span>
						Категории
					</a>
					<a href="?page=otpravka_sost" class="list-group-item <?php echo $_GET['page'] == 'otpravka_sost'?'active':''; ?>">
						<span class="glyphicon glyphicon-th"></span>
						Статус
					</a>
					<a href="?page=options" class="list-group-item <?php echo $_GET['page'] == 'options'?'active':''; ?>">
						<span class="glyphicon glyphicon-cog"></span>
						Настройки
					</a>
					<a href="?page=worker" class="list-group-item <?php echo $_GET['page'] == 'worker'?'active':''; ?>">
						<span class="text-success glyphicon glyphicon-user"></span>
						Пользователи системы
					</a>
					
					<?php
					if (in_array($_SESSION['__ID__'],$super_admin))
					{
					?>
					<a target="_blank" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/programm_files/php_admin.php?server=<?php echo $hostDB; ?>&username=<?php echo $userDB; ?>&password=<?php echo $passDB; ?>&db=<?php echo $baseDB; ?>" class="list-group-item">
						<span class="glyphicon glyphicon-tasks"></span>
						База данных
					</a>
					<?php
					}
					?>
					
					<a href="?exit" class="list-group-item">
						<span class="glyphicon glyphicon-off"></span>
						Выход
					</a>
				</div>
			</div>
		</div><!-- col-md-2	-->
		<div class="col-md-10">	
		<div class="row">	
		<?php

		if (isset($_GET['page']))
		{
			if (file_exists(ABSOLUTE__PATH__.'/programm_files/admin_pages/'.$_GET['page'].'.php'))
			{
			include_once(ABSOLUTE__PATH__.'/programm_files/admin_pages/'.$_GET['page'].'.php');
			
			}
			else
				{
				include_once(ABSOLUTE__PATH__.'/programm_files/admin_pages/404.php');
				}
		}
		else
			{
			include_once(ABSOLUTE__PATH__.'/programm_files/admin_pages/main.php');
			}
		
	isset($mysql)?mysqli_close($mysql):'';
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	
	$memory = (function_exists('memory_get_usage')?memory_get_usage():0);
    $memory = $memory - $orig_memory;	
 

	?>
		</row>
		</div><!-- col-md-10-->
	</div>
</div>

	<div class="col-md-12"> 
		<div class="row text-center">
			<p class="footer_text">Страница сгенерирована за <?php echo round($time, 3); ?> сек. | Памяти затрачено <?php echo filesize_get($memory); ?> </p>
		</div>
	</div>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="js/Easy-Searchable-Filterable-Select-List-with-jQuery/jquery.searchableSelect.js"></script>
	<script src="js/bootstrap.js"></script>	
	<script src="js/calendar_ru2.js"></script>
	<script type="text/javascript">
  
	if ($('select.search').length) {	
	$('select.search').searchableSelect();
	}
	
	$('#ves_gruz, #ob_gruz').bind('input load change click', calcAndShow); 
	function calcAndShow() { 
	var v1 = parseFloat($("#ves_gruz").val().replace(/,/, '.')); 
	var v2 = parseFloat($("#ob_gruz").val().replace(/,/, '.')); 
	var it = v1 / v2;
	$("#itogo").html(parseFloat(it.toFixed(2))); 
	$("#itogo_val").val(parseFloat(it.toFixed(2))); 
	}
	
	$('#date_otprav, #date_postav').bind('input load change click', count_diff); 
	function count_diff() {
	var d1 = $("#date_otprav").val().replace(/,/, '.'); 
	var d2 = $("#date_postav").val().replace(/,/, '.'); 
	var date_1 = d1 !== '' ? new Date(parseInt(d1.substr(6, 4), 10), parseInt(d1.substr(3, 2), 10), parseInt(d1.substr(0, 2), 10)) : ''; 
	var date_2 = d2 !== '' ? new Date(parseInt(d2.substr(6, 4), 10), parseInt(d2.substr(3, 2), 10), parseInt(d2.substr(0, 2), 10)) : ''; 
		if (d1 !== '' && d2 !== '')
		{
		var it = (d1 == d2) ? '1' : ((date_2 - date_1)/86400000)+1;
		}
		else
			{
			var it = '';
			}

	$("#kol_day").html(it); 
	$("#kol_day_val").val(it);
	}
	
	$('#strah_inv, #inv_price').bind('input load change click', strah); 
	function strah() {
	var d1 = parseFloat($("#inv_price").val().replace(/,/, '.')); 
		if ($("#strah_inv option:selected").val() == '0')
		{
		var d2 = '0';
		}
		else
			{
			var d2 = $("#strah_inv option:selected").val();
			}

	var it = d2 > 0 ? (d1 * d2)/100:0;

	$("#strah_inv_sum").val(it);
	}


	$(document).ready(function() {
		$("#client_id").change(function()
		{
			if ($("#client_id option:selected").val() == '0')
			{
			$("#client_id").addClass('hidden');
			$("#client_id_new").removeClass('hidden');
			}
			else
				{
				$("#client_id").removeClass('hidden');
				$("#client_id_new").addClass('hidden');
				}
		});
		
		$('#close_client_id_new').on('click', function() {
			$("#client_id").removeClass('hidden');
			$("#client_id_new").addClass('hidden');
		});	
		
		$('.add_podopech').click(function(){
			$("#add_podopech").removeClass('hidden');
			$("#btn_podopech").addClass('hidden');
		});	
		
		$('.add_podopech_close').click(function(){
			$("#add_podopech").addClass('hidden');
			$("#btn_podopech").removeClass('hidden');
		});
		
		$('.searchable-select-item').click(function () {
		var categor_id = $(this).data("value");
		if (categor_id == '0')
		{
			$(".searchable-select").addClass('hidden');
			$("#categor_id").addClass('hidden');
			$("#categor_id_new").removeClass('hidden');
		}
		else
			{
				$(".searchable-select").removeClass('hidden');
				$("#categor_id").removeClass('hidden');
				$("#categor_id_new").addClass('hidden');
			}
		});
		
		$("#categor_id").change(function()
		{
			if ($("#categor_id option:selected").val() == '0')
			{
			$("#categor_id").addClass('hidden');
			$("#categor_id_new").removeClass('hidden');
			}
			else
				{
				$("#categor_id").removeClass('hidden');
				$("#categor_id_new").addClass('hidden');
				}
		});
		
		$('#close_categor_id_new').on('click', function() {
			$(".searchable-select").removeClass('hidden');
			$("#categor_id").removeClass('hidden');
			$("#categor_id_new").addClass('hidden');
		});	
		
		<?php $reremennay = '1'; ?>
		$("#sost").change(function()
		{
			if ($("#sost option:selected").val() == '<?php echo $reremennay; ?>')
			{
			$("#tip_sklad").removeClass('hidden');
			}
			else
				{
				$("#tip_sklad").addClass('hidden');
				}
		});
		
	});	
	</script>
</body>
</html>

<?php 

	$fileonline = file(ABSOLUTE__PATH__.'/programm_files/online.srz');
			if (isset($titlepanel))
			{
			$gde = $titlepanel;
			}
			else
				{
				$gde = str_replace('/rupka.php','',$_SERVER['REQUEST_URI']);
				}
			$saveline = '';
			foreach($fileonline as $online)
			{
			$exl=explode("|",$online);

				if ($exl[0] == $_SESSION['__ID__'])
				{
				$saveline .= $_SESSION['__ID__']."|".time()."|".str_replace('/rupka.php','',$_SERVER['REQUEST_URI'])."|".$gde."|\r\n";
				$save_status = true;
				}
				else
					{
					$save_status2 = true;
					$saveline .= $exl[0]."|".$exl[1]."|".$exl[2]."|".$exl[3]."|\r\n";
					}
			}

			if (isset($save_status))
			{
			$on_l=fopen(ABSOLUTE__PATH__.'/programm_files/online.srz', 'w');
			$saves = $saveline;
			}
			elseif (!isset($save_status) or !isset($save_status2))
				{
				$on_l=fopen(ABSOLUTE__PATH__.'/programm_files/online.srz', 'a+');
				$saves = $_SESSION['__ID__']."|".time()."|".str_replace('/rupka.php','',$_SERVER['REQUEST_URI'])."|".$gde."|\r\n";
				}
			fputs($on_l,$saves);
			fclose($on_l);
	
?>

