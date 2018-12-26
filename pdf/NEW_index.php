<?php
// 	setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251', 'CP1251'); 
//     header("Content-Type:text/xml;charset=utf-8");
	define('ABSOLUTE__PATH__',$_SERVER['DOCUMENT_ROOT']);
	
	if (isset($_POST['html']))
	{
	$html = base64_decode(trim($_POST['html']));
	}
	else
		{
		$html = '
		<table border="1">
			<tr>
				<td>Русский текст</td>
				<td>Русский текст</td>
				<td>Русский текст</td>
				<td>Русский текст</td>
			</tr>
			<tr>
				<td>Русский текст</td>
				<td>Русский текст</td>
				<td>Русский текст</td>
				<td><a href="http://mpdf.bpm1.com/" title="mPDF">mPDF</a></td>
			</tr>
		</table>';
		}
include("src/Mpdf.php");
//Кодировка | Формат | Размер шрифта | Шрифт
//Отступы: слева | справа | сверху | снизу | шапка | подвал
$mpdf = new mPDF('utf-8', 'A4', '10', 'Arial', 10, 10, 7, 7, 10, 10);
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
$mpdf->Output(ABSOLUTE__PATH__.'/mpdf-development/mpdf_html.pdf', 'I');

echo file_get_contents(ABSOLUTE__PATH__.'/mpdf-development/mpdf_html.pdf');
?>