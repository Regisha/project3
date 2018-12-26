<?php
	if (!defined('__PANEL__BOARD__'))
	{
	die ("<meta http-equiv=refresh content='0; url=http://".$_SERVER['HTTP_HOST']."/kpp.php'>");
	}
	
	mysqli_query($mysql,"CREATE TABLE IF NOT EXISTS GRUZ_options(
	id int auto_increment primary key,
	orgname varchar(100) NOT NULL,
	orgmail varchar(100) NOT NULL,
	orgtel varchar(100) NOT NULL,
	orgadres varchar(100) NOT NULL,
	orgcomment TEXT NOT NULL
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Опции, настроки'");	
	
	if (isset($_POST['SBM_add']))
	{
	$orgname = mysqli_real_escape_string($mysql, trim($_POST['orgname']));
	$orgmail = mysqli_real_escape_string($mysql, trim($_POST['orgmail']));
	$orgtel = mysqli_real_escape_string($mysql, trim($_POST['orgtel']));
	$orgadres= mysqli_real_escape_string($mysql, trim($_POST['orgadres']));
	$orgcomment = mysqli_real_escape_string($mysql, trim($_POST['orgcomment']));
	
		if (isset($_POST['id']))
		{
		$query_count = "UPDATE GRUZ_options SET orgname='". $orgname."',orgmail='". $orgmail ."', orgtel='". $orgtel ."', orgadres='". $orgadres ."', orgcomment='". $orgcomment."' WHERE id='".intval($_POST['id'])."' LIMIT 1";
		mysqli_query($mysql,$query_count) or trigger_error(mysqli_error($mysql)." in ".$query_count);
		}
		else
			{
			$insertSQL = mysqli_query($mysql, "INSERT INTO GRUZ_options (orgname,orgmail,orgtel,orgadres,orgcomment) 
			VALUES ('".$orgname."','".$orgmail."','".$orgtel."','".$orgadres."','". $orgcomment."')");
			if(!$insertSQL) die(trigger_error(mysqli_error($mysql)." in ".$insertSQL));
			}
			
	die ("<meta http-equiv=refresh content='0; url=?page=options&mess=1'>");
	}
	?>

  	<div class="col-md-12">
		<h3>Настройки</h3>
		<div class="clearfix mtop"><br></div>
		<?php
		if (isset($_GET['edit']))
		{
		$SQL = "SELECT * FROM GRUZ_options LIMIT 1";
		$r = mysqli_query($mysql, $SQL) or  die(mysqli_error().' - FIRM_menu_cat');
		$arr = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		}
		
		if (isset($_GET['mess']))
		{
		?>
		<div class="alert alert-dismissible alert-success col-md-8 col-md-offset-2">
			<h3 class="text-center text-success">Настройки сохранены</h3>
			<p class="text-center">
				<a class="btn btn-success btn-sm" href="?page=options">Хорошо</a>
			</p>
		</div>
		<div class="clearfix mtop"></div>
		<?php
		}
		?>
	
		<div class="col-md-12 alert alert-warning mtop">
			<h3>Информация об организации</h3>
			<form class="form-horizontal" method="POST" role="form">
			
				<?php echo isset($arr['id'])?'<input type="hidden" name="id" value="'.$arr['id'].'"/>':''; ?>
				
				<div class="form-group">
					<label for="orgname" class="col-lg-3 control-label">Название организации</label>
					<div class="col-lg-8">
						<input class="form-control" name="orgname" type="text" value="<?php echo isset($arr['orgname']) ? $arr['orgname'] : ''; ?>"   placeholder="КаргоТранс" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="orgmail" class="col-lg-3 control-label">@mail</label>
					<div class="col-lg-8">
						<input class="form-control" name="orgmail" type="text" value="<?php echo isset($arr['orgmail']) ? $arr['orgmail'] : ''; ?>"   placeholder="@mail.ru" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="orgtel" class="col-lg-3 control-label">Тел. организации</label>
					<div class="col-lg-8">
						<input class="form-control" name="orgtel" type="text" value="<?php echo isset($arr['orgtel']) ? $arr['orgtel'] : ''; ?>"   placeholder="+7..." />
					</div>
				</div>
				
				<div class="form-group">
					<label for="orgadres" class="col-lg-3 control-label">Адрес организации</label>
					<div class="col-lg-8">
						<input class="form-control" name="orgadres" type="text" value="<?php echo isset($arr['orgadres']) ? $arr['orgadres'] : ''; ?>"   placeholder="Красноярский край, г. Красноярск....." />
					</div>
				</div>
						
				<div class="form-group">
					<label for="comment_client" class="col-lg-3 control-label"> Комментарий</label>
					<div class="col-lg-8">
						<textarea class="form-control" name="comment_client" rows="3"  placeholder="Комментарий"> <?php echo isset($arr['comment_client'])?html_entity_decode(html_entity_decode($arr['comment_client'])):'';?></textarea>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" name="SBM_add" class="btn btn-primary">Сохранить</button>
						<a class="btn btn-sm btn-danger" href="?page=options">Отменить</a>
					</div>
				</div>
			
			</form>
			<div class="clearfix"></div>
		</div>

	</div>
