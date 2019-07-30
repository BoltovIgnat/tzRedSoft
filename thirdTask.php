<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");

$string = file_get_contents("chart2.json");

$data = json_decode($string);

for($r=0; $r <count($data); $r++)
{
	for($c=0; $c <count($data[$r]); $c++)
	{
		if (($data[$r][$c] == 100 or $data[$r][$c] == null) 
				and ($data[$r+1][$c] == 100 or $data[$r+1][$c] == null) 
				and ($data[$r+2][$c] == 100 or $data[$r+2][$c] == null) 
				and ($data[$r+3][$c] == 100 or $data[$r+3][$c] == null)){

			$data[$r][$c] = null;
			$data[$r+1][$c] = null;
			$data[$r+2][$c] = null;
			$data[$r+3][$c] = null;

		}

	}
}

$json = json_encode($data);
// создаем новый файл
$file = fopen('chart_result.json', 'w');
// и записываем туда данные
$write = fwrite($file,$json);
// проверяем успешность выполнения операции
if($write) echo "Данные успешно записаны!<br>";
else echo "Не удалось записать данные!<br>";
//закрываем файл
fclose($file);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>