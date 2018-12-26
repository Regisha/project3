<?php
	if (!defined('__PANEL__BOARD__'))
	{
	die ("<meta http-equiv=refresh content='0; url=http://".$_SERVER['HTTP_HOST']."/kpp.php'>");
	}
	
	mysqli_query($mysql,"CREATE TABLE IF NOT EXISTS GRUZ_client(
		client_id int auto_increment primary key,
		admin_id int(1) NOT NULL,
		num_client varchar(30) NOT NULL,
		fio_client varchar(100) NOT NULL,
		city_client varchar(100) NOT NULL,
		address_client varchar(150) NOT NULL,
		tel_client varchar(100) NOT NULL,
		mail_client varchar(100) NOT NULL,
		posred varchar(100) NOT NULL,
		comment_client	 varchar(200) NOT NULL
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Клиенты'");	
		if (isset($_POST['SBM_add']))
	{
		
		$admin_id = mysqli_real_escape_string($mysql, $_SESSION['__ID__']);
		$num_client = mysqli_real_escape_string($mysql, trim($_POST['num_client']));
		$fio_client = mysqli_real_escape_string($mysql, trim($_POST['fio_client']));
		$city_client= mysqli_real_escape_string($mysql, trim($_POST['city_client']));
		$address_client= mysqli_real_escape_string($mysql, trim($_POST['address_client']));
		$tel_client = mysqli_real_escape_string($mysql, trim($_POST['tel_client']));
		$mail_client = mysqli_real_escape_string($mysql, trim($_POST['mail_client']));
		$posred = mysqli_real_escape_string($mysql, trim($_POST['posred']));
		$comment_client = mysqli_real_escape_string($mysql, trim($_POST['comment_client']));
	
		if (isset($_POST['client_id']))
		{
		$query_count = "UPDATE GRUZ_client SET num_client='". $num_client."',fio_client='". $fio_client ."', city_client='". $city_client ."', address_client='". $address_client ."', tel_client='". $tel_client."', mail_client='". $mail_client."', posred='". $posred."', comment_client='". $comment_client."' WHERE client_id='".intval($_POST['client_id'])."' LIMIT 1";
		mysqli_query($mysql,$query_count) or trigger_error(mysqli_error($mysql)." in ".$query_count);
		}
		else
			{
			$insertSQL = mysqli_query($mysql, "INSERT INTO GRUZ_client (admin_id,num_client,fio_client,city_client,address_client,tel_client,mail_client,posred, comment_client) 
			VALUES 	
				('".$admin_id."','".$num_client."','".$fio_client."','".$city_client."','". $address_client."', '". $tel_client."','".$mail_client."','".$posred."','".$comment_client."')");
			if(!$insertSQL) die(trigger_error(mysqli_error($mysql)." in ".$insertSQL));
			}
			
	die ("<meta http-equiv=refresh content='0; url=?page=client'>");
	}
	
	if (isset($_GET['del']))
	{
		$query = "DELETE FROM GRUZ_client  WHERE client_id='" .  intval($_GET['del']) . "' LIMIT 1";
		mysqli_query($mysql,$query) or die(mysqli_error());
		
	die ("<meta http-equiv=refresh content='0; url=?page=client'>");
	}	
?>

  	<div class="col-md-12">
		<h3>Клиенты</h3>
		<a class="btn btn-primary" href="?page=client&add=true"><span class="glyphicon glyphicon-plus"></span> Добавить клиента</a>
		<div class="clearfix mtop"><br></div>
	<?php
	if (isset($_GET['delete']))
	{
	?>
	<div class="alert alert-dismissible alert-danger col-md-8 col-md-offset-2">
		<h3 class="text-center text-danger">Подтверждение удаления клиента #<?php echo $_GET['delete']; ?></h3>
		<p class="text-center">
			<a class="btn btn-danger btn-sm" href="?page=client">Отмена</a>
			<a class="btn btn-success" href="?page=client&del=<?php echo $_GET['delete']; ?>"><span class="glyphicon glyphicon-trash"></span> Удалить</a>
		</p>
	</div>
	<div class="clearfix"></div>
	<?php
	}
	elseif (isset($_GET['add']))
	{
		if (isset($_GET['edit']))
		{
		$SQL = "SELECT * FROM GRUZ_client WHERE client_id='". intval($_GET['edit']) ."' LIMIT 1";
		$r = mysqli_query($mysql, $SQL) or  die(mysqli_error().' - FIRM_menu_cat');
		$arr = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		}
		
?>
	
	<div class="col-md-12 alert alert-warning mtop">
			<h3>Информация о клиенте</h3>
			<form class="form-horizontal" method="POST" role="form">	
			<?php
				echo isset($arr['client_id'])?'<input type="hidden" name="client_id" value="'.$arr['client_id'].'"/>':'';
			?>
		
			<div class="col-md-12 alert alert-warning mtop">
				<div class="form-group">
					<label for="num_client" class="col-lg-3 control-label">  Номер клиента: </label>
						<div class="col-lg-8">
							<input class="form-control" name="num_client" type="text" value="<?php echo isset($arr['num_client']) ? $arr['num_client'] : ''; ?>"   placeholder="Введите № клиента" required />
						</div>
				</div>			
			
				<div class="form-group has-error">
					<label for="fio_client" class="col-lg-3 control-label">ФИО получателя/клиента</label>
						<div class="col-lg-8">
							<input class="form-control" name="fio_client" type="text" value="<?php echo isset($arr['fio_client']) ? $arr['fio_client'] : ''; ?>"   placeholder="Введите ФИО клиента" required />
						</div>
				</div>
				
				
				<div class="form-group has-error">
					<label for="city_client" class="col-lg-3 control-label">Город</label>
						<div class="col-lg-8">
							<input class="form-control" name="city_client" type="text" value="<?php echo isset($arr['city_client']) ? $arr['city_client'] : ''; ?>"  placeholder="Введите город клиента" required />
						</div>
				</div>
				
				
				
				<div class="form-group has-error">
					<label for="address_client" class="col-lg-3 control-label">Адрес</label>
						<div class="col-lg-8">
							<input class="form-control" name="address_client" type="text" value="<?php echo isset($arr['address_client']) ? $arr['address_client'] : ''; ?>"  placeholder="Введите адрес: улица, дом, корпус" required />
						</div>
				</div>
				
				
		
				<div class="form-group has-error">
					<label for="tel_client" class="col-lg-3 control-label">Телефон клиента</label>
						<div class="col-lg-8">
							<input class="form-control" name="tel_client" type="text" value="<?php echo isset($arr['tel_client']) ? $arr['tel_client'] : ''; ?>"  placeholder="Введите № телефона клиента" required />
						</div>
				</div>
				
				
				<div class="form-group">
					<label for="mail_client" class="col-lg-3 control-label">Email клиента</label>
						<div class="col-lg-8">
							<input class="form-control" name="mail_client" type="text" value="<?php echo isset($arr['mail_client']) ? $arr['mail_client'] : ''; ?>"  placeholder="Введите Email клиента"/>
						</div>
				</div>
				
				<div class="form-group">
					<label for="posred" class="col-lg-3 control-label">Контрагент/посредник</label>
						<div class="col-lg-8">
							<input class="form-control" name="posred" type="text"  value="<?php echo isset($arr['posred']) ? $arr['posred'] : ''; ?>" placeholder="Контрагент (посредник)"  />
						</div>
				</div>
				
				<div class="form-group">
                    <label for="comment_client" class="col-lg-3 control-label"> Комментарий</label>
                    <div class="col-lg-8">
                        <textarea class="form-control" name="comment_client" rows="3"  placeholder="Комментарий"> <?php echo isset($arr['comment_client'])?html_entity_decode(html_entity_decode($arr['comment_client'])):'';?>
                        </textarea>
                    </div>
			</div>
				
				
				
		</div>	
	</div>	
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" name="SBM_add" class="btn btn-primary">Сохранить</button>
				<a class="btn btn-sm btn-danger" href="?page=client">Отменить</a>
			</div>
		</div>
		
	</form>
	<div class="clearfix"></div>
</div>

<?php
}
else
		{
		?>
		<table class="table table-hover">
			<?php
			?>
			<tr>
				<th> № </th>
				<th>ФИО</th>
				<th>№ телефона</th>
				<th>Управление</th>
			</tr>
			<?php
			$x=1;
			$r = mysqli_query($mysql,"SELECT * FROM GRUZ_client");
				if(!$r) exit(mysqli_error());
				while	($hk=mysqli_fetch_assoc($r))
				{
				?>
				<tr>
					<td><?php echo $hk['num_client']; ?> </td>
					<td>
					<a  href="?page=client&add=true&edit=<?php echo $hk['client_id']; ?>"><?php echo $hk['fio_client']; ?></a> </td>
					
					<td><?php echo  $hk['tel_client']; ?></td>
					<td>
						<a class="btn btn-sm btn-success" href="?page=client&add=true&edit=<?php echo $hk['client_id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
						<a class="btn btn-sm btn-danger" href="?page=client&delete=<?php echo $hk['client_id']; ?>"><span class="glyphicon glyphicon-trash"></span></a>
					</td>
				</tr>
				<?php
				$x++;
				}
				
			mysqli_free_result($r);
			?>
		</table>
		<?php
		}
		?>
	</div>
