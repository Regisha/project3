<?php
	if (!defined('__PANEL__BOARD__'))
	{
	die ("<meta http-equiv=refresh content='0; url=http://".$_SERVER['HTTP_HOST']."/kpp.php?login'>");
	}

		
	?>
	
	<div class="col-md-12">
	<h3 class="page-header">Главная</h3>
		<div class="col-md-8">
		<?php
		if (in_array($_SESSION['__ID__'],$super_admin))
		{
		?>
			<h3>Активность пользователей</h3>
			<table class="table table-hover">
				<tr>
					<th>Статус</th>
					<th>user</th>
					<th>Активность</th>
					<th>Где</th>
				</tr>
			<?php
			foreach (file(ABSOLUTE__PATH__.'/programm_files/online.srz') as $line)
			{
			$line = trim($line);
			$exp = explode ('|',$line);
				if (($exp[1]+2592000) > time())
				{
				?>
				<tr class="<?php echo (($exp[1]+1200) > time()) ? 'success' : 'danger'; ?>">
					<td><?php echo (($exp[1]+1200) > time()) ? '<b class="text-success">online</b>' : '<b class="text-danger">offline</b>'; ?></td>
					<td>
						<a href="?page=worker&see=<?php echo $exp[0]; ?>">
							<?php echo isset($user[$exp[0]])?$user[$exp[0]]['name']:$exp[0]; ?>
						</a>
					</td>
					<td><?php echo date('H:i, d.m.Y', $exp[1]); ?></td>
					<td>
						<a href="<?php echo !empty($exp[2]) ? $exp[2] : '?page=main'; ?>">
							<?php echo !empty($exp[3]) ? $exp[3] : $exp[2]; ?>
						</a>
					</td>
				</tr>
			<?php
				}
			}
			?>
			</table>
		<?php
		}
		?>
		</div>
		
		<div class="col-md-4">
			
			<script>var calendru_c='';var calendru_mc='';var calendru_dc='';var calendru_c_all='';var calendru_n_l=1;var calendru_n_s=0;var calendru_n_d=0;var calendru_i_f=1;var calendru_show_names = 0;</script><script src=http://www.calend.ru/img/export/informer_new_theme1u.js?></script>
			<div class="clearfix"></div>
		</div>

	</div><!--col-md-12-->
