<?php
	setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251', 'CP1251'); 
    header("Content-Type:text/xml;charset=utf-8");
	define('ABSOLUTE__PATH__',$_SERVER['DOCUMENT_ROOT']);
	session_start();
	
	if (isset($_POST['html']))
	{
	$html = base64_decode(trim($_POST['html']));
	}
	else
		{
		if (!isset($_SESSION['__ID__']))
		{
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/kpp.php?login');
			exit;
		}

		define( '__PANEL__BOARD__', 1 );
		
		include_once(ABSOLUTE__PATH__.'/programm_files/mysql.php');
		include_once(ABSOLUTE__PATH__.'/programm_files/functions.php');
		
		if (isset($_GET['otpravka_id']))
		{
		$SQL = "SELECT * FROM GRUZ_otpravka WHERE otpravka_id='". intval($_GET['otpravka_id']) ."' LIMIT 1";
		$r = mysqli_query($mysql, $SQL) or  die(mysqli_error().' - FIRM_menu_cat');
		$arr = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		
		$SQL = "SELECT * FROM GRUZ_client WHERE client_id='". $arr['client_id'] ."' LIMIT 1";
		$r = mysqli_query($mysql, $SQL) or  die(mysqli_error().' - FIRM_menu_cat');
		$cli = mysqli_fetch_assoc($r);
		mysqli_free_result($r);	
		
		$SQL = "SELECT * FROM GRUZ_categor WHERE id='". $arr['categor_id'] ."' LIMIT 1";
		$r = mysqli_query($mysql, $SQL) or  die(mysqli_error().' - FIRM_menu_cat');
		$cat = mysqli_fetch_assoc($r);
		mysqli_free_result($r);	
		
		$SQL = "SELECT * FROM GRUZ_options  LIMIT 1";
		$r = mysqli_query($mysql, $SQL) or  die(mysqli_error().' - FIRM_menu_cat');
		$org = mysqli_fetch_assoc($r);
		mysqli_free_result($r);	
		
		
		$html = '
		<table class="table" border="1">
		<tr>
			<td colspan="10" align="center"> <img src="../logo.png"/  img style="width:70%"></td>
		</tr>
		<tr>
			<td colspan="10" align="left" style="font-size:1.0em; color:#0000FF; ">E-mail:  '.$org['orgmail'].'</td>
		</tr>
		<tr>
			<th colspan="10" align="center" style="font-size:1.5em; color:#FF0000;"> Транспортная накладная № '.$org['otpravka_id'].'</th>
		</tr>
		
		<tr>
			<th colspan="10" align="left" style="font-size:1.5em; color:#FF0000;">  Пункт назначения </th>
		</tr>
		<tr>
			<th align="left" colspan="5"> Отправитель: '.$org['orgname'].'</th>
			<th align="left" colspan="5">Тел: '.$org['orgtel'].'</th> 
		</tr>
		<tr>
			<th align="left" colspan="5">Получатель:  '.$cli['fio_client'].'</th>
			<th align="left" colspan="5">Тел: '.$cli['tel_client'].'</th>
		</tr>
		
		<tr>
			
			<th align="left" colspan="5">Вид транспортироваки: '.( (isset($arr['tip_otprav']) AND $arr['tip_otprav'] == 1) ? 'Авиа -  срок доставки 8-10 дней ' : 'Авто - срок доставки 18-20 дней, максимальная выдача 25 дней' ).'</th>
			<th align="left" colspan="5">Склад отправки: '.$cli['orgadres'].'</th>
		</tr>
		<tr>
			<th align="left" colspan="4">Срок перевозки:</th>
			<th align="left" colspan="5">Максимальный срок выдачи товара:</th>
		</tr>
		<tr>
			<th align="center" >Наименование:</th>
			<th align="center">Кол-во мест:</th>
			<th align="center">Вес Брутто, кг</th>
			<th align="center">Объем, м3</th>
			
			<th align="center">Страховка</th>
			<th align="center">Упаковка, $</th>
			<th align="center">Доп. начисления, $</th>
			<th align="center">Ставка за перевозку, $</th>
			
			<th align="center">Инвойс, $</th>
		</tr>
		<tr>
			<td align="center">' .$cat['name'] .'</td>
			<td align="center">'.$arr['kol_mest'].'</td>                         
			<td align="center">'.$arr['ves_gruz'].'</td>
			<td align="center">'.$arr['ob_gruz'].'</td>
			
			<td align="center">'.$arr['strah_inv_sum'].'</td>
			<td align="center">'.$arr['upakovka'].'</td>
			<td align="center"> '.$arr['dop_nach'].'</td>
			<td align="center"> '.$arr['stavka_perevoz_zn'].'</td>
			
			<td align="center">'.$arr['inv_price'].'</td>
		</tr>
		<tr>
			<th bgcolor="Yellow" > Итого к оплате,  '.$arr['itog'].'</th>
			<th align="center">'.$arr['kol_mest'].' </th>
			<th align="center">'.$arr['ves_gruz'].'</th>
			<th align="center">'.$arr['ob_gruz'].' </th>
			
			<th align="center">'.$arr['strah_inv_sum'].' </th>
			<th align="center">'.$arr['upakovka'].' </th>
			<th  align="center">'.$arr['dop_nach'].' </th>
			<td align="center"> '.$arr['stavka_perevoz_zn'].'</td>
			<th align="center">'.$arr['inv_price'].' </th>
			
		</tr>
		<tr>
			<td colspan="9">
			Руководство компании просит ВАС при получении груза произвести обязательную проверку внутренней и внешней сторон
			упаковки,проверку на наличие груза. В случае утери имущества, порчи, повреждений внутренней и внешней сторон упаковки,
			немедленно обратитесь в представительство нашей компании, для составления акта о некорректном получении груза. Если Вы
			не получаете акт и не заявляете о составление акта, мы полагаем что Вы получили груз без повреждения, разницы в весе (что
			означает утерю груза ).
			Материальная компенсация в случае порчи и утери груза производится в Китае!
			</td>
		</tr>
		<tr>
			<td colspan="9">
			*Дополнительные начисления отсутствуют
			</td>
		</tr>	
	</table>';
	mysqli_free_result($r);
		}
		}
include("mpdf.php");
//Кодировка | Формат | Размер шрифта | Шрифт
//Отступы: слева | справа | сверху | снизу | шапка | подвал
$mpdf = new mPDF('utf-8', 'A4', '14', 'Arial', 10, 10, 7, 7, 10, 10);
// $mpdf->charset_in = 'utf-8';

$stylesheet = 'body { font-family: Arial, Helvetica, sans-serif; }
h1 {
font-size:1.5em;
}
h1,h2,h3 {
text-align:center;
}
table.table {
	border: 1px solid black;
	border-collapse: collapse;
}
td.table {
	text-align: center;
	border: 1px solid black;
}
tr.table {
	text-align: center;
	border: 1px solid black;
}';

//Записываем стили
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->list_indent_first_level = 0;
//Записываем html
$mpdf->WriteHTML($html, 2);
$mpdf->Output(ABSOLUTE__PATH__.'/mpdf60/mpdf_html.pdf', 'I');

echo file_get_contents(ABSOLUTE__PATH__.'/mpdf60/mpdf_html.pdf');
?>
