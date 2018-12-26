<?php
	if (!defined('__PANEL__BOARD__'))
	{
	die ("<meta http-equiv=refresh content='0; url=http://".$_SERVER['HTTP_HOST']."/kpp.php'>");
	}
	
	mysqli_query($mysql,"CREATE TABLE IF NOT EXISTS GRUZ_otpravka(
		otpravka_id int auto_increment primary key,
		admin_id int(1) NOT NULL,
		client_id int(20) NOT NULL,
		sost_zayavki  int(5) NOT NULL,
		num_vnut varchar(20) NOT NULL,
		kol_mest int(20) NOT NULL,
		tip_otprav  int(2) NOT NULL,
		tip_sklad int(2) NOT NULL,
		ves_gruz varchar(20) NOT NULL,
		ob_gruz varchar(20) NOT NULL,
		avt_pole varchar(100) NOT NULL,
		date_otprav varchar(12) NOT NULL,
		date_postav varchar(12) NOT NULL,
		kol_day int(3) NOT NULL,
		inv_price int(10) NOT NULL,
		strah_inv int(10) NOT NULL,
		strah_inv_sum  int(4) NOT NULL,
		stavka_perevoz int(2) NOT NULL,
		stavka_perevoz_zn varchar(20) NOT NULL,
		dop_nach int(10) NOT NULL,
		com_dop_nach varchar(80) NOT NULL,
		upakovka int(10) NOT NULL,
		itog varchar(100) NOT NULL,
		comment_otp varchar(500) NOT NULL,
		categor_id int(10) NOT NULL
		
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Клиенты'");	
	
	
	mysqli_query($mysql,"CREATE TABLE IF NOT EXISTS GRUZ_time(
        otpravka_id int(5) NOT NULL,
		admin_id int(10) NOT NULL,
		status int(10) NOT NULL,
		time varchar(12) NOT NULL
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Время'");	
		
	
	$arr_sost = array();
	$arr_sost[0] = array('sost' => 'Без категории');
	$r = mysqli_query($mysql,"SELECT * FROM CRM_otpravka_sost ORDER BY status");
		if(!$r) exit(mysqli_error());
		while	($hk=mysqli_fetch_assoc($r))
		{
		$arr_sost[$hk['status']] = $hk;
		}
	mysqli_free_result($r);	
	
	$arr_client=array();
	$r=mysqli_query($mysql,"SELECT * FROM GRUZ_client");
        if(!$r) exit(mysqli_error());
        while ($hk=mysqli_fetch_assoc($r))
        {
        $arr_client[$hk['client_id']]=$hk;
        }
      mysqli_free_result($r);  
	
	$arr_tip_otprav[1] = array('status' => 'Авиа');
	$arr_tip_otprav[2] = array('status' => 'Авто');
	
	
	$arr_sklad[1] = array('status' => 'Гуанчжоу');
	$arr_sklad[2] = array('status' => 'Иу');
	$arr_sklad[3] = array('status' => 'Урумчи');
	$arr_sklad[4] = array('status' => 'Фошань');
	
	
	$arr_stavka[1] = array('status' => 'м/3');
	$arr_stavka[2] = array('status' => 'кг');
	
    if (isset($_POST['SBM_add']))
	{
// 		echo '<pre>';
// 			print_r($_POST);
// 		echo '</pre>';
		$admin_id = mysqli_real_escape_string($mysql, $_SESSION['__ID__']);
		$client_id = mysqli_real_escape_string($mysql, trim($_POST['client_id']));
		$sost_zayavki = mysqli_real_escape_string($mysql, trim($_POST['sost_zayavki']));
		$num_vnut = mysqli_real_escape_string($mysql, trim($_POST['num_vnut']));
		$kol_mest = mysqli_real_escape_string($mysql, trim($_POST['kol_mest']));
		$tip_otprav= mysqli_real_escape_string($mysql, trim($_POST['tip_otprav']));
		$tip_sklad= (isset($_POST['tip_sklad']) AND ($_POST['tip_sklad']== 'ERR'))?'':intval($_POST['tip_sklad']);
		$ves_gruz= mysqli_real_escape_string($mysql, trim($_POST['ves_gruz']));
		$ob_gruz = mysqli_real_escape_string($mysql, trim($_POST['ob_gruz']));
		
		$avt_pole= isset($_POST['avt_pole'])?intval($_POST['avt_pole']):'';
		
		$date_otprav = isset($_POST['date_otprav'])?strtotime($_POST['date_otprav']):'';
		$date_postav = isset($_POST['date_postav'])?strtotime($_POST['date_postav']):'';
		
		$kol_day= isset($_POST['kol_day'])?intval($_POST['kol_day']):0;
		$inv_price = mysqli_real_escape_string($mysql, trim($_POST['inv_price']));
		$strah_inv = intval($_POST['strah_inv']);
		$strah_inv_sum = intval($_POST['strah_inv_sum']);
		
		$stavka_perevoz = mysqli_real_escape_string($mysql, trim($_POST['stavka_perevoz']));
		$stavka_perevoz_zn = mysqli_real_escape_string($mysql, trim($_POST['stavka_perevoz_zn']));
		$dop_nach = mysqli_real_escape_string($mysql, trim($_POST['dop_nach']));
		$com_dop_nach = mysqli_real_escape_string($mysql, trim($_POST['com_dop_nach']));
		$upakovka= mysqli_real_escape_string($mysql, trim($_POST['upakovka']));
        
		$itog= mysqli_real_escape_string($mysql, summa_replace($_POST['itog']));
		
		$comment_otp= mysqli_real_escape_string($mysql, trim($_POST['comment_otp']));
		$categor_id = mysqli_real_escape_string($mysql, trim($_POST['categor_id']));
		
		$categor_id = 0;
		
		if (isset($_POST['categor_id']))
		{
			if ($_POST['categor_id'] == 0)
			{
			$cat_name = mysqli_real_escape_string($mysql, trim($_POST['cat_id_new']));
			$insertSQL = mysqli_query($mysql, "INSERT INTO GRUZ_categor (name,opis) VALUES ('".$cat_name."','')");
			if(!$insertSQL) die(mysqli_error());
			$categor_id = mysqli_insert_id($mysql);
			}	
			else
				{
				$categor_id = mysqli_real_escape_string($mysql, trim($_POST['categor_id']));
				}	
		}
	
		if (isset($_POST['otpravka_id']))
		{
// 		echo '<pre>';
// 			print_r($_POST);
// 		echo '</pre>';
		$otpravka_id = intval($_POST['otpravka_id']);
		$query_count = "UPDATE GRUZ_otpravka SET client_id='". $client_id."',sost_zayavki='". $sost_zayavki."' ,num_vnut='".$num_vnut."',kol_mest='". $kol_mest ."', tip_otprav='". $tip_otprav ."',tip_sklad='". $tip_sklad ."',ves_gruz='". $ves_gruz ."', ob_gruz='". $ob_gruz."', avt_pole='". $avt_pole."', date_otprav='". $date_otprav."', date_postav='". $date_postav."',kol_day='". $kol_day."',inv_price='". $inv_price ."', strah_inv='". $strah_inv ."', strah_inv_sum='". $strah_inv_sum ."',  stavka_perevoz='". $stavka_perevoz ."', stavka_perevoz_zn='". $stavka_perevoz_zn ."', dop_nach='". $dop_nach."', com_dop_nach='". $com_dop_nach."', upakovka='". $upakovka."', itog='". $itog."', comment_otp='". $comment_otp."', categor_id='". $categor_id."' WHERE otpravka_id='".$otpravka_id."' LIMIT 1";
		mysqli_query($mysql,$query_count) or trigger_error(mysqli_error($mysql)." in ".$query_count);
		}
		else
			{
			$insertSQL = mysqli_query($mysql, "INSERT INTO GRUZ_otpravka (admin_id,client_id,sost_zayavki,num_vnut,kol_mest,tip_otprav,tip_sklad,ves_gruz,ob_gruz,avt_pole,date_otprav, date_postav,kol_day,inv_price,strah_inv,strah_inv_sum,stavka_perevoz,stavka_perevoz_zn,dop_nach,com_dop_nach,upakovka,itog,comment_otp,categor_id) 
			VALUES 	
				('".$admin_id."','".$client_id."','".$sost_zayavki."','".$num_vnut."','".$kol_mest."','".$tip_otprav."','".$tip_sklad."','". $ves_gruz."', '". $ob_gruz."','".$avt_pole."','".$date_otprav."','".$date_postav."','', '". $inv_price."','".$strah_inv."','".$strah_inv_sum."', '".$stavka_perevoz."','".$stavka_perevoz_zn."','".$dop_nach."','".$com_dop_nach."','".$upakovka."','','".$comment_otp."','".$categor_id."')");
                if(!$insertSQL) die(trigger_error(mysqli_error($mysql)." in ".$insertSQL));
                $otpravka_id = mysqli_insert_id($mysql);
			
			$query_count = "UPDATE GRUZ_otpravka SET kol_mest='". $kol_mest."', itog='". $itog."'  WHERE otpravka_id='".$otpravka_id."' LIMIT 1";
				mysqli_query($mysql,$query_count) or trigger_error(mysqli_error($mysql)." in ".$query_count);
			}
			
        $SQL = "SELECT * FROM GRUZ_time WHERE otpravka_id='".$otpravka_id."' AND admin_id='".$admin_id."' AND status='".$sost_zayavki."'  LIMIT 1";
		$r = mysqli_query($mysql, $SQL) or  die(mysqli_error().' - FIRM_menu_cat');
		$arr = mysqli_fetch_assoc($r);
		mysqli_free_result($r);		
		if (!isset ($arr['otpravka_id']))
		{
            $insertSQL = mysqli_query($mysql, "INSERT INTO GRUZ_time (otpravka_id,admin_id,status,time) 
			VALUES 	
            ('".$otpravka_id."','".$admin_id."','".$sost_zayavki."','".time()."')");
            if(!$insertSQL) die(trigger_error(mysqli_error($mysql)." in ".$insertSQL));
            
		}
	//die ("<meta http-equiv=refresh content='0; url=?page=otpravka&add=true&edit=".$otpravka_id."&mess=save'>");
	}
	
	if (isset($_GET['del']))
	{
		$query = "DELETE FROM GRUZ_otpravka  WHERE otpravka_id='" .  intval($_GET['del']) . "' LIMIT 1";
		mysqli_query($mysql,$query) or die(mysqli_error());
		
	die ("<meta http-equiv=refresh content='0; url=?page=otpravka'>");
	}	
?>

  	<div class="col-md-12">
		<h3>Отправка груза</h3>
		<a class="btn btn-primary" href="?page=otpravka&add=true"><span class="glyphicon glyphicon-plus"></span> Оформить отправку груза</a>
		<a href="?page=otpravka_sost" class="btn btn-default">Добавить статус к грузу</a>
		<div class="clearfix mtop"><br></div>
	<?php
	
	
	if (isset($_GET['mess']))
	{
	?>
	<div class="alert alert-dismissible alert-success col-md-8 col-md-offset-2">
		<h3 class="text-center text-success">Запись сохранена</h3>
		<p class="text-center">
			<a class="btn btn-success btn-sm" href="?page=otpravka&add=true&edit=<?php echo $_GET['edit']; ?>">Хорошо</a>
		</p>
	</div>
	<div class="clearfix"></div>
	<?php
	}
	if (isset($_GET['delete']))
	{
	?>
	<div class="alert alert-dismissible alert-danger col-md-8 col-md-offset-2">
		<h3 class="text-center text-danger">Подтверждение удаления отправки #<?php echo $_GET['delete']; ?></h3>
		<p class="text-center">
			<a class="btn btn-danger btn-sm" href="?page=otpravka">Отмена</a>
			<a class="btn btn-success" href="?page=otpravka&del=<?php echo $_GET['delete']; ?>"><span class="glyphicon glyphicon-trash"></span> Удалить</a>
		</p>
	</div>
	<div class="clearfix"></div>
	<?php
	}
	elseif (isset($_GET['add']))
	{
		if (isset($_GET['edit']))
		{
		$SQL = "SELECT * FROM GRUZ_otpravka WHERE otpravka_id='". intval($_GET['edit']) ."' LIMIT 1";
		$r = mysqli_query($mysql, $SQL) or  die(mysqli_error().' - FIRM_menu_cat');
		$arr = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		}
		
?>
	
	<div class="col-md-12 alert alert-warning mtop">
		<h3>Информация о грузе</h3>
		<form class="form-horizontal" method="POST" role="form">	
		<?php echo isset($arr['otpravka_id'])?'<input type="hidden" name="otpravka_id" value="'.$arr['otpravka_id'].'"/>':''; ?>
			
						
			<div class="form-group">
				<label for="sost_zayavki" class="col-lg-3 control-label">Статус груза:</label>
					<div class="col-lg-8">
						<select class="form-control" name="sost_zayavki" id="sost" <?php echo $user[$_SESSION['__ID__']]['status'] == 0?'':'readonly'; ?>>
							<?php
							foreach ($arr_sost as $hk)
							{
							?>
							<option <?php echo (isset($arr['sost_zayavki']) and $arr['sost_zayavki'] == $hk['status']) ? 'selected' : ''; ?> value="<?php echo $hk['status']; ?>"><?php echo $hk['status']; ?> - <?php echo $hk['name']; ?></option>
							<?php	
							}
							?>
						</select>
					</div>
			</div>
			
			<div id="tip_sklad" class="form-group <?php echo (isset($arr['sost_zayavki']) AND $arr['sost_zayavki'] == '1') ? '' : 'hidden'; ?>">
				<label for="tip_sklad" class="col-lg-3 control-label">Склад </label>
				<div class="col-lg-8">
					<select class="form-control" name="tip_sklad" <?php echo $user[$_SESSION['__ID__']]['status'] == 0?'':'readonly'; ?>>
						<option value="ERR" selected disabled>Выбрать</option>
						<?php 
						foreach ($arr_sklad as $key => $hk)
						{
						?>
						<option <?php echo (isset($arr['tip_sklad']) and $arr['tip_sklad'] == $key) ? 'selected' : ''; ?> value="<?php echo isset($_POST['ERR']) ? '' : $key; ?>"><?php echo $hk['status']; ?></option>
						<?php	
						}
						?>
					</select>	
				</div>
			</div>
						
			<div class="form-group">
				<label for="num_client" class="col-lg-3 control-label">  Номер клиента: </label>
				<div class="col-lg-5">
					<select class="form-control search" name="client_id" <?php echo $user[$_SESSION['__ID__']]['status'] == 0?'required':'readonly'; ?>>
						<option selected disabled>Выбрать</option>
						<?php 
						$result = mysqli_query($mysql, "SELECT * FROM GRUZ_client ORDER BY client_id+0 DESC") or  die(mysqli_error($mysql).' - GRUZ_client');
						if(!$result) exit(mysqli_error($mysql));
						while ($dt = mysqli_fetch_assoc($result))
						{
						?>
						<option <?php echo (isset($arr['client_id']) and $arr['client_id'] == $dt['client_id']) ? 'selected' : ''; ?> value="<?php echo $dt['client_id']; ?>"><?php echo $dt['num_client']." - ".$dt['fio_client']; ?></option>
						<?php 
						}
						?>
					</select>
				</div>
				
				
				
				<div class="col-lg-3">
					<input class="form-control" name="num_vnut" type="text" value="<?php echo isset($arr['num_vnut']) ? $arr['num_vnut'] : ''; ?>"   placeholder="Внутренний №" <?php echo $user[$_SESSION['__ID__']]['status'] == 0?' ':'readonly'; ?>/>
				</div>
			</div>
				
			<div class="form-group has-error">
				<label for="kol_mest" class="col-lg-3 control-label">Количество мест в данной отправке</label>
				<div class="col-lg-8">
					<input class="form-control" name="kol_mest" type="text" value="<?php echo isset($arr['kol_mest']) ? $arr['kol_mest'] : ''; ?>"   placeholder="Введите количество мест" <?php echo $user[$_SESSION['__ID__']]['status'] == 0?'required':'readonly'; ?>/>
				</div>
			</div>
				
			<div class="form-group">
				<label for="tip_otprav" class="col-lg-3 control-label">Тип отправки</label>
				<div class="col-lg-8">
					<select class="form-control" name="tip_otprav" <?php echo $user[$_SESSION['__ID__']]['status'] == 0?'':'readonly'; ?>>
						<option selected disabled>Выбрать</option>
						<?php 
						foreach ($arr_tip_otprav as $key => $hk)
						{
						?>
						<option <?php echo (isset($arr['tip_otprav']) and $arr['tip_otprav'] == $key) ? 'selected' : ''; ?> value="<?php echo $key; ?>"><?php echo $hk['status']; ?></option>
						<?php	
						}
						?>
					</select>	
				</div>
			</div>
			
			
			
			
			
				
			<div class="form-group has-error">
				<label for="categor_id" class="col-lg-3 control-label">Наименование/категория груза (для удобной вставки можно сделать список категорий)</label>
				<div class="col-lg-8">
					<select class="form-control <?php echo $user[$_SESSION['__ID__']]['status'] == 0?'search':''; ?>" name="categor_id" id="categor_id" <?php echo $user[$_SESSION['__ID__']]['status'] == 0?'required':'readonly'; ?>>
						<option <?php echo !isset($arr['categor_id']) ? 'selected' : ''; ?> disabled>Выбрать</option>
						<option value="0">Добавить новую категорию</option>
						<?php
						$result = mysqli_query($mysql, "SELECT * FROM GRUZ_categor ORDER BY name ASC") or  die(mysqli_error($mysql).' - GRUZ_categor');
						if(!$result) exit(mysqli_error($mysql));
						while ($hk = mysqli_fetch_assoc($result))
						{
						?>
						<option <?php echo (isset($arr['categor_id']) and $arr['categor_id'] == $hk['id']) ? 'selected' : ''; ?> value="<?php echo $hk['id']; ?>"><?php echo $hk['name']; ?></option>
						<?php	
						}
						?>
					</select>
					
					<div class="col-md-12 hidden" id="categor_id_new">
						<div class="col-md-11">
							<input class="form-control" name="categor_id_new" type="text" value="" placeholder="Простое название Категории"/>
						</div>
						<div class="col-md-1"><a class="mybutton m5 btn btn-danger btn-sm" href="javascript:void(0)" id="close_categor_id_new"><span class="glyphicon glyphicon-remove"></span></a></div>
					</div>						
				</div>
			</div>
				
			<div class="form-group">
				<label for="ves_gruz" class="col-lg-3 control-label">Вес Брутто,кг</label>
				<div class="col-lg-8">
					<input class="form-control" name="ves_gruz" id="ves_gruz" type="text" value="<?php echo isset($arr['ves_gruz']) ? $arr['ves_gruz'] : ''; ?>"  placeholder="Введите вес груза"/>
				</div>
			</div>
				
			<div class="form-group">
				<label for="ob_gruz" class="col-lg-3 control-label">Объём, m3</label>
				<div class="col-lg-8">
					<input class="form-control" name="ob_gruz" id="ob_gruz" type="text" value="<?php echo isset($arr['ob_gruz']) ? $arr['ob_gruz'] : ''; ?>"  placeholder="Введите объём груза"/>
				</div>
			</div>
				
			<div class="form-group">
				<label for="avt_pole" class="col-lg-3 control-label">Автоматически заполняемое поле "объёмного веса" (вес делить на объем)</label>
				<div class="col-lg-8"  name="avt_pole">
					<?php
					$ves_gruz = '';
					if (isset ($arr['ves_gruz']) AND ($arr['ob_gruz']))
					{
					$a1=(summa_replace($arr['ves_gruz'])/summa_replace($arr['ob_gruz']));
					$ves_gruz = $a1;
					}
					else 
						{
						$ves_gruz = "0"; 
						}
					?>
					<input class="form-control" name="avt_pole" id="itogo_val" type="text" value="<?php  echo (isset ($arr['ves_gruz']) AND ($arr['ob_gruz']))?number_format($ves_gruz, 2) : ''; ?>"  placeholder="вес делим на объем" readonly />
				</div>
			</div>
				
			<div class="form-group">
				<label for="date_otprav" class="col-lg-3 control-label">Дата отправки</label>
				<div class="col-lg-8">
					<input class="form-control" id="date_otprav" name="date_otprav" type="text"  value="<?php echo (isset($arr['date_otprav']) AND !empty($arr['date_otprav']) )?date('d.m.Y',$arr['date_otprav']):date('d.m.Y'); ?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" />
				</div>
			</div>
				
			<div class="form-group">
				<label for="date_postav" class="col-lg-3 control-label">Дата доставки</label>
				<div class="col-lg-8">
					<input class="form-control" id="date_postav" name="date_postav" type="text"  value="<?php echo (isset($arr['date_postav']) AND !empty($arr['date_postav']) )?date('d.m.Y',$arr['date_postav']):''; ?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" />
				</div>
			</div>
				
			<div class="form-group">
				<label for="kol_day" class="col-lg-3 control-label">Количество дней (заполняется автоматически методом вычитания даты отправки от даты доставки)</label>
				<div class="col-lg-8">
					<?php
					$a = (isset($arr['date_postav']) AND !empty($arr['date_postav']) ) ? dateInterval (date('d.m.Y',$arr['date_otprav']), date('d.m.Y',$arr['date_postav'])) : array();
					?>
					<input class="form-control" name="kol_day" id="kol_day_val" type="text"  value="<?php echo isset($arr['date_postav']) ? count($a) : ''; ?>" disabled/>
				</div>
			</div>
				
			<div class="form-group">
				<label for="inv_price" class="col-lg-3 control-label">Инвойсовая стоимость груза</label>
				<div class="col-lg-8">
					<input class="form-control" name="inv_price" id="inv_price" type="text"  value="<?php echo isset($arr['inv_price']) ? $arr['inv_price'] : ''; ?>" />
				</div>
			</div>
				
				
			<div class="form-group">
				<label for="strah_inv" class="col-lg-3 control-label"> Страховка,% </label>
				<div class="col-lg-8">
					<select class="form-control" name="strah_inv" id="strah_inv">
						<?php
						for ($i=0; $i<100; $i++)
						{
						?>
						<option <?php echo (isset($arr['strah_inv']) AND ($arr['strah_inv']) == $i)?'selected':'';?> value="<?php echo $i; ?>"><?php  echo (isset($i) AND ($i) ==0)?'Страховка не предусмотрена':$i. "% от инвойсовой стотимости"; ?></option>
						<?php 
						}
						?>
					</select>
				</div>
			</div>
					
			<div class="form-group">
				<label for="inv_price" class="col-lg-3 control-label"> Рассчет страховки (Инв. стоим. груза*Страховка,%), $</label>
				<div class="col-lg-8">
					<?php
					$strah_inv_sum = '';
					if (isset($arr['strah_inv']) and $arr['strah_inv'] !== 0)
					{
					$strah_inv_sum =  (($arr['strah_inv'])*($arr['inv_price']))/100;
					}
					else 
						{
						$strah_inv_sum =  "0";
						}
					?>
					<input class="form-control" name="strah_inv_sum" id="strah_inv_sum" type="text" value="<?php echo $strah_inv_sum; ?>" placeholder="" readonly/>
				</div>
			</div>		
				
			<div class="form-group has-error">
				<label for="stavka_perevoz" class="col-lg-3 control-label">Ставка на перевозку, $</label>
				<div class="col-lg-4">
					<select class="form-control" name="stavka_perevoz">
						<option selected disabled>Выбрать</option>
						<?php 
						foreach ($arr_stavka as $key => $hk)
						{
						?>
						<option <?php echo (isset($arr['stavka_perevoz']) and $arr['stavka_perevoz'] == $key) ? 'selected' : ''; ?> value="<?php echo $key; ?>"><?php echo $hk['status']; ?></option>
						<?php	
						}
						?>
					</select>		
				</div>
						
				<div class="col-lg-4">
					<input class="form-control" name="stavka_perevoz_zn" type="text"  value="<?php echo isset($arr['stavka_perevoz_zn']) ? $arr['stavka_perevoz_zn'] : '0'; ?>" placeholder=""/>
				</div>
			</div>
				
			<div class="form-group">
				<label for="dop_nach" class="col-lg-3 control-label"> Доп. начисления, $ </label>
				<div class="col-lg-2">
					<input class="form-control" name="dop_nach" type="text"  value="<?php echo isset($arr['dop_nach']) ? $arr['dop_nach'] : '0'; ?>" placeholder=""/>
				</div>
						
				<div class="col-lg-6">
					<input class="form-control" name="com_dop_nach" type="text"  value="<?php echo isset($arr['com_dop_nach']) ? $arr['com_dop_nach'] : ''; ?>" placeholder="в комментариях отметить причину начисления"/>
				</div>
			</div>
				
			<div class="form-group">
				<label for="upakovka" class="col-lg-3 control-label">Упаковка, $ </label>
				<div class="col-lg-8">
					<input class="form-control" name="upakovka" type="text"  value="<?php echo isset($arr['upakovka']) ? $arr['upakovka'] : '0';?>"  placeholder="(сумма за упаковку партии груза)"/>
				</div>
			</div>
				
			<div class="form-group">
				<label for="itog" class="col-lg-3 control-label">Итого к оплате</label>
				<div class="col-lg-8">
					<?php
					//   По формулам расчета. Если выбрана ставка в м/3, то «м/3 х объем». Если выбрана кг, то «кг х вес брутто».
					//50 кг груз, 2 доллара за кило = 100 долларов + 120 долларов за страховку, + 15 допы + 10 упаковка
					$peremennay = '';
					if (isset($arr['stavka_perevoz']) and $arr['stavka_perevoz'] == 1)
					{
					//$ves_gruz = $ves_gruz == 0 ? 1 : $ves_gruz;
					$peremennay = ($arr['ob_gruz']*$arr['stavka_perevoz_zn']) + $strah_inv_sum+$arr['dop_nach']+$arr['upakovka'];
					echo 'Формула: ('.$arr['ob_gruz'].'*'.$arr['stavka_perevoz_zn'].') + '.$strah_inv_sum.' + '.$arr['dop_nach'].' + '.$arr['upakovka'];
					}
					elseif (isset($arr['stavka_perevoz']) and $arr['stavka_perevoz'] == 2)
					{
					//$ves_gruz = $arr['ves_gruz'] == 0 ? 1 : $arr['ves_gruz'];
					$peremennay = ($arr['ves_gruz']*$arr['stavka_perevoz_zn']) + $strah_inv_sum+$arr['dop_nach']+$arr['upakovka'];
					echo 'Формула: ('.$arr['ves_gruz'].'*'.$arr['stavka_perevoz_zn'].') + '.$strah_inv_sum.' + '.$arr['dop_nach'].' + '.$arr['upakovka'];
					}
					else 
						{
						$peremennay =  "0";
						}
					?>
					<input name="itog" type="hidden" value="<?php echo $peremennay; ?>"/>
					<input class="form-control" name="itog1" type="text"  value="<?php echo $peremennay; ?> $" placeholder="ставка*вес или объем+страховка+доп.начисления+упаковка" readonly >
				</div>
			</div>
				
			<div class="form-group">
				<label for="comment_otp" class="col-lg-3 control-label"> Комментарий</label>
				<div class="col-lg-8">
					<textarea class="form-control" name="comment_otp" rows="3"  placeholder="Комментарий"><?php echo isset($arr['comment_otp'])?html_entity_decode(html_entity_decode($arr['comment_otp'])):'';?>
					</textarea>
				</div>
			</div>
				

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" name="SBM_add" class="btn btn-primary">Сохранить</button>
					<a class="btn btn-sm btn-danger" href="?page=otpravka">Отменить</a>
					<?php
					if (isset($arr['otpravka_id']))
					{
					?>
					<span class="pull-right"><a target="_blank" class="btn btn-lg btn-default" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/pdf6/NEW_index.php?otpravka_id=<?php echo $arr['otpravka_id']; ?>"><span class="glyphicon glyphicon-floppy-save"></span></a></span>
					<?php
					}
					?>
				</div>
			</div>
		</form>
		<div class="clearfix"></div>
	</div>
		<?php
		if (isset($arr['otpravka_id']))
		{
		?>
		<div class="clearfix"></div>
		<h3>Движение статусов по данному грузу</h3>
		<table class="table table-hover">
			<tr>
				<th>Пользователь</th>
				<th>Дата</th>
				<th>Статус</th>
			</tr>
			<?php
			$SQL = "SELECT * FROM GRUZ_time WHERE otpravka_id='".$arr['otpravka_id']."' ORDER BY time DESC";
			$r = mysqli_query($mysql,$SQL);
			if(!$r) exit(mysqli_error());
			while	($dt=mysqli_fetch_assoc($r))
			{
			?>
			<tr>
				<td><?php echo $user[$dt['admin_id']]['name']; ?></td>
				<td><?php echo date('d.m.Y H:i',$dt['time']); ?></td>
				<td><?php echo $arr_sost[$dt['status']]['name']; ?></td>
			</tr>
			<?php
			}
			mysqli_free_result($r);
			?>
		</table>
		<?php
		}
	}
	else
		{
		
        $WHERE = '';
        if (isset($_GET['q']))
        {
        $q = trim($_GET['q']);
 
        $WHERE = "AND GRUZ_client.num_client LIKE '%".$q."%' OR GRUZ_client.fio_client LIKE '%".$q."%' OR GRUZ_otpravka.otpravka_id='".$q."' OR GRUZ_otpravka.num_vnut='".$q."' ";   
        
        }
        ?>
        <div class="col-md-12 well well-sm">
            <form action="" method="GET" name="forma1">
            <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
                <div class="form-group">
                    <div class="col-lg-9 text-right">
                        <input name="q" class="form-control input-md" placeholder="Введите № клиенета, ФИО клиента или номер отправки" value="<?php echo isset($_GET['q'])?$_GET['q']:''; ?>" type="text" />
                    </div>
                    <div class="col-lg-3 text-right">
                        <button type="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok"> </span>Искать </button>
                    </div>
                </div>	
            </form>
        </div>
        
        <div class="clearfix"></div>
            
		
		<?php
		foreach ($arr_sost as $k => $v)
		{
		?>
		<a class="label label-<?php echo (isset($_GET['sost_zayavki']) AND $_GET['sost_zayavki'] == $k)?'success':'default'; ?>" href="?page=otpravka&sost_zayavki=<?php echo $k; ?>"><span class="glyphicon glyphicon-<?php echo (isset($_GET['sost_zayavki']) AND $_GET['sost_zayavki'] == $k)?'check':'unchecked'; ?>"></span> <?php echo $arr_sost[$k]['name']; ?></a>
		<?php
		}
		if (isset($_GET['sost_zayavki']))
		{
		?>
		<a class="label label-danger" href="?page=otpravka"><span class="label label-danger"></span> <?php echo "Очистить"; ?></a>
		<?php
		}
		?>
		<div class="clearfix mtop"><br></div>
		<?php 
		$sostsort = isset($_GET['sost_zayavki']) ? " AND GRUZ_otpravka.sost_zayavki='".intval($_GET['sost_zayavki'])."'" : '';
		
				$SQL = "
					SELECT 
							GRUZ_otpravka.otpravka_id,
							GRUZ_otpravka.categor_id,
							GRUZ_otpravka.num_vnut,
							GRUZ_otpravka.date_otprav,
							GRUZ_otpravka.date_postav,
							GRUZ_otpravka.itog,
							GRUZ_otpravka.sost_zayavki,
							GRUZ_otpravka.ob_gruz,
							GRUZ_otpravka.ves_gruz,
							GRUZ_otpravka.stavka_perevoz_zn,
							GRUZ_client.client_id,
							GRUZ_client.num_client,
							GRUZ_client.fio_client,
							GRUZ_client.address_client,
							GRUZ_client.tel_client,
							GRUZ_time.time,
							GRUZ_categor.name
							
					FROM 
						GRUZ_otpravka 
					LEFT JOIN  
						GRUZ_client
					ON 
						GRUZ_otpravka.client_id=GRUZ_client.client_id
						
					
					LEFT JOIN  
						GRUZ_categor
					ON 
						GRUZ_otpravka.categor_id=GRUZ_categor.id
						
					LEFT JOIN  
						GRUZ_time
					ON 
						GRUZ_otpravka.otpravka_id=GRUZ_time.otpravka_id
						
					WHERE
						GRUZ_otpravka.otpravka_id > 0 
						 ".$sostsort."	".$WHERE."	
					
                    GROUP BY GRUZ_otpravka.otpravka_id
                    
                    ORDER
                        BY GRUZ_otpravka.otpravka_id DESC,
                        GRUZ_time.time DESC";
						
						
		
			
		?>
		<table class="table table-hover">
			<tr>
				<th>Hомер отправки</th>
				<th> Дата отправки</th>
				<th> Hомер клиента</th>
				<th> Объем</th>
				<th> Вес</th>
				<th>Ставка</th>
				<th> Стоимость</th>
				<th> Статус</th>
				<th>Управление</th>
			</tr>
			<?php
			$r = mysqli_query($mysql,$SQL);
			if(!$r) exit(mysqli_error());
			while	($arr=mysqli_fetch_assoc($r))
			{
                $color = 'list-group-item';
                if ($arr['sost_zayavki'] == '0')
                {
                $color = 'list-group-item-success';
                }
			?>
				<tr>
				
					<td>  <?php if ($user[$_SESSION['__ID__']]['status'] == 0)
                         {
                         ?>
                         <a   href="?page=otpravka&add=true&edit=<?php echo $arr['otpravka_id']; ?>"><?php echo $arr['otpravka_id']; ?> </a> </td>
                         <?php 
						 }
						 else 
						 {
						 ?>
						 <a   href="?page=otpravka&see=<?php echo $arr['otpravka_id']; ?>"><?php echo $arr['otpravka_id']; ?> </a> </td>
						 <?php 
						 }
						 ?>
					<td><?php echo  date ('d.m.Y',$arr['date_otprav']); ?></td>
					<td><?php echo $arr['num_client'].(!empty($arr['num_vnut'])?'-'.$arr['num_vnut']:'') ; ?> </td>
					<td><?php echo $arr['ob_gruz']; ?> </td>
					<td><?php echo $arr['ves_gruz']; ?> </td>
					<td><?php echo isset($arr['stavka_perevoz_zn']) ? $arr['stavka_perevoz_zn'] : '0'; ?></td>
					<td><?php echo $arr['itog']; ?> </td>
					<td class="list-group-item <?php echo $color; ?>"><?php echo $arr_sost[$arr['sost_zayavki']]['name']; ?>
					<br> <?php  echo isset($arr['time'])?"Изменено: ".date('d.m.Y', $arr['time'])." ". date('H:i:s', $arr['time']):''; ?>
					
					</td>
					
					<td>
                         <?php if ($user[$_SESSION['__ID__']]['status'] == 0)
                         {
                         ?>
						<a class="btn btn-sm btn-success" href="?page=otpravka&add=true&edit=<?php echo $arr['otpravka_id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
						<a class="btn btn-sm btn-danger" href="?page=otpravka&delete=<?php echo $arr['otpravka_id']; ?>"><span class="glyphicon glyphicon-trash"></span></a>
						<a target="_blank" class="btn btn-sm btn-default" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/pdf6/NEW_index.php?otpravka_id=<?php echo $arr['otpravka_id']; ?>"><span class="glyphicon glyphicon-floppy-save"></span></a>
						<?php 
						}
						else 
						{
						?>
						<a data-toggle="tooltip" data-placement="top" title="Просмотр" class="btn btn-primary btn-sm" href="?page=otpravka&add&edit=<?php echo $arr['otpravka_id']; ?>"><span class="glyphicon glyphicon-eye-open"></span></a>
						<?php 
						}
						?>
					</td>
				</tr>
				<?php
				}
			mysqli_free_result($r);
			?>
		</table>
		<?php
		}
		?>
	</div>
