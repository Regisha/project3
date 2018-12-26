<?php
	if (!defined('__PANEL__BOARD__'))
	{
	die ("<meta http-equiv=refresh content='0; url=http://".$_SERVER['HTTP_HOST']."inter.php?login'>");
	}
	
	mysqli_query($mysql,"CREATE TABLE IF NOT EXISTS CRM_otpravka_sost(
	id int auto_increment primary key,
	name varchar(300) NOT NULL,
	status int(3) NOT NULL,
	settings text NOT NULL
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Статусы задач'");	
	
	
	
	
	if (isset($_POST['Sbmt_start']))
	{
	$name = mysqli_real_escape_string($mysql, trim($_POST['name']));
	$status = intval($_POST['status']);
	$query = "INSERT INTO CRM_otpravka_sost (name,status,settings) VALUES('".$name."','".$status."','')";
	mysqli_query($mysql,$query) or die(mysqli_error());
	
	die ("<meta http-equiv=refresh content='0; url=?page=otpravka_sost'>");
	}
	
	if (isset($_GET['edit']))
	{
	$r = mysqli_query($mysql,"SELECT * FROM CRM_otpravka_sost WHERE id='". (int)$_GET['edit'] ."' LIMIT 1");
		if(!$r) exit(mysqli_error());
		$arredit = mysqli_fetch_assoc($r);
	mysqli_free_result($r);
	}
	
	if (isset($_POST['Sbmt_edit']))
	{
	$name = mysqli_real_escape_string($mysql, trim($_POST['name']));
	$status = intval($_POST['status']);	
	$query_count = "UPDATE CRM_otpravka_sost SET name='".$name."',status='".$status."' WHERE id='".$_POST['id']."' LIMIT 1";
	mysqli_query($mysql,$query_count) or trigger_error(mysqli_error()." in ".$query_count);
	
	die ("<meta http-equiv=refresh content='0; url=?page=otpravka_sost'>");
	}
	
	if (isset($_GET['delete']))
	{
	?>
	<div class="alert alert-dismissible alert-danger">
		<h3 class="text-center text-danger">Подтверждение удаления</h3>
		<strong>Выбранная запись будет удалена из базы</strong><br /> 
		<p>Осторожно, это может привести к последствиям, если данная категория уже использовалась в системе, тогда будет вызвана ошибка в той записи.</p>
		
		<p class="text-center">
			<input type="button" value=" Удалить " onclick="location.href = '?page=otpravka_sost&del=<?php echo $_GET['delete']; ?>';return false;" class="btn btn-danger btn-sm"/>
			<input type="button" value=" Отмена " onclick="location.href = '?page=otpravka_sost';return false;" class="btn btn-primary btn-sm"/>
		</p>
	</div>
	
	<div class="clearfix"></div>
	<?php
	}	
	
	if (isset($_GET['del']))
	{
		$query = "DELETE FROM CRM_otpravka_sost WHERE id='" .  intval($_GET['del']) . "'";
		mysqli_query($mysql,$query) or die(mysqli_error());
	die ("<meta http-equiv=refresh content='0; url=?page=otpravka_sost'>");
	}	

?>
	<div class="col-md-12">
	<h3>Заказы - Статус</h3>
	<div class="clearfix"></div>
	<br />
	<div class="clearfix"></div>


	<div class="col-md-12 well bs-component">
	<a name="adds"></a>
	<form class="form-horizontal" action='' method='post'>
		<fieldset>
			<legend>Поля выделенные <b class="text-danger">красным*</b>, обязательны к заполнению.</legend>
		    
			<div class="form-group has-error">
				<label for="name" class="col-lg-2 control-label">Состояние*</label>
				<div class="col-lg-10">
					<input class="form-control" name="name" type="text" value="<?php echo isset($arredit['name']) ? $arredit['name'] : ''; ?>" required />
				</div>
			</div>
			
			<div class="form-group has-error">
				<label for="status" class="col-lg-2 control-label">Порядок выполнения*</label>
				<div class="col-lg-10">
					<input class="form-control" name="status" type="text" value="<?php echo isset($arredit['status']) ? $arredit['status'] : ''; ?>" placeholder="Только цифра" required />
				</div>
			</div>
			
			<?php echo isset($arredit) ? '<input name="id" type="hidden" value="'.$arredit['id'].'" />' : ''; ?>
		
			<div class="form-group">
				<div class="col-lg-10 col-lg-offset-2">
					<button type="submit" class="btn btn-primary" name="<?php echo isset($arredit) ? 'Sbmt_edit' : 'Sbmt_start'; ?>" onclick="this.value='Публикуется';"><?php echo isset($arredit) ? 'Изменить' : 'Добавить'; ?></button>
					
					<?php echo isset($arredit) ? '<input type="button" value=" Отмена " onclick="location.href = \'?page=otpravka_sost\';return false;" class="btn btn-danger"/>' : ''; ?>
				</div>
			</div>		

		</fieldset>
		</form>	
	</div>
	
	<div class="clearfix"></div>	
	
		<table class="table table-hover">
		
			<tr>
				<th>Состояние</th>
				<th>Порядок выполнения</th>
				<th>Операции</th>
			</tr>	
		<?php
			$i=0;
				$SQL="SELECT * FROM  CRM_otpravka_sost  ORDER BY id ASC";
				$B_user = mysqli_query($mysql,$SQL);
				if(!$B_user) exit(mysqli_error($mysql));
				while($gm=mysqli_fetch_assoc($B_user))
			{
			?>
			<tr>
				<td><?php echo $gm['name']; ?></td>
				<td><?php echo $gm['status']; ?></td>
				<td><a data-toggle="tooltip" data-placement="top" title="Редактировать" class="btn btn-success btn-sm" href="?page=otpravka_sost&edit=<?php echo $gm['id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
				<a data-toggle="tooltip" data-placement="top" title="Удалить" class="btn btn-danger btn-sm" href="?page=otpravka_sost&delete=<?php echo $gm['id']; ?>"><span class="glyphicon glyphicon-remove"></span></a></td>
			</tr>		
			<?php	
			$i++;
			}
		mysqli_free_result($B_user);	
		?>
		</table>

	<div class="clearfix"></div>
	</div>
