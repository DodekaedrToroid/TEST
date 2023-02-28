<?
require( $_SERVER[ "DOCUMENT_ROOT" ] . "/bitrix/header.php" );

$time_start = microtime( true );
echo ' ';
define( "NO_KEEP_STATISTIC", true );
define( "NOT_CHECK_PERMISSIONS", true );
$deleteFiles = 'yes'; //Удалять ли найденые файлы yes/no
//Целевая папка для поиска файлов
$rootDirPath = $_SERVER[ 'DOCUMENT_ROOT' ] . "/upload/iblock";
// Получаем записи из таблицы b_file
$arFilesCache = array();
$result = $DB->Query( 'SELECT FILE_NAME, SUBDIR FROM b_file WHERE MODULE_ID = "iblock"' );
while ( $row = $result->Fetch() ) {
  $arFilesCache[ $row[ 'FILE_NAME' ] ] = $row[ 'SUBDIR' ];
}
$hRootDir = opendir( $rootDirPath );
$count = 0;
$contDir = 0;
$countFile = 0;
$i = 1;
$removeFile = 0;
while ( false !== ( $subDirName = readdir( $hRootDir ) ) ) {
  if ( $subDirName == '.' || $subDirName == '..' ) {
    continue;
  }
  //Счётчик пройденых файлов
  $filesCount = 0;
  $subDirPath = "$rootDirPath/$subDirName"; //Путь до подкатегорий с файлами
  $hSubDir = opendir( $subDirPath );
  while ( false !== ( $fileName = readdir( $hSubDir ) ) ) {
    if ( $fileName == '.' || $fileName == '..' ) {
      continue;
    }
    $countFile++;
    if ( array_key_exists( $fileName, $arFilesCache ) ) { //Файл с диска есть в списке файлов базы - пропуск
      $filesCount++;
      continue;
    }
    $fullPath = "$subDirPath/$fileName"; // полный путь до файла
    $backTrue = false; //для создание бэкапа
    if ( $deleteFiles === 'yes' ) {
      if ( !file_exists( $patchBackup . $subDirName ) ) {
        if ( CheckDirPath( $patchBackup . $subDirName . '/' ) ) { //создал поддиректорию
          $backTrue = true;
        }
      } else {
        $backTrue = true;
      }
      if ( $backTrue ) {
        if ( $saveBackup === 'yes' ) {
          CopyDirFiles( $fullPath, $patchBackup . $subDirName . '/' . $fileName ); //копия в бэкап
        }
      }
      //Удаление файла
      if ( unlink( $fullPath ) ) {
        $removeFile++;
        echo "Удалил: " . $fullPath . '
';
      }
    } else {
      $filesCount++;
      echo 'Кандидат на удаление - ' . $i . ') ' . $fullPath . '
';
    }
    $i++;
    $count++;
    unset( $fileName, $backTrue );
  }
  closedir( $hSubDir );
  //Удалить поддиректорию, если удаление активно и счётчик файлов пустой - т.е каталог пуст
  if ( $deleteFiles && !$filesCount ) {
    rmdir( $subDirPath );
  }
  $contDir++;
}
?>

<div class="container">
  <h1>Результат автоматического удаления мусора из папки upload</h1>
  <div class="my_row">
    <div class="my-col-8">
      <?if ($count < 1) {?>
      <p>
        <?echo 'Не нашёл данных для удаления';?>
      </p>
      <?}?>

      <p>
        <?echo 'Всего файлов удалил: <strong>' . $removeFile . '</strong>';?>
      </p>
      <p>
        <?echo 'Всего файлов в ' . $rootDirPath . ': ' . $countFile . '';?>
      </p>
      <p>
		<?echo 'Всего подкаталогов в ' . $rootDirPath . ': ' . $contDir . '';?>
	  <p>
		<?echo 'Всего записей в b_file: ' . count($arFilesCache) . '';?>
	 </p>	  
<?closedir($hRootDir);?>
		  <p>
<?echo ''; $time_end = microtime(true); $time = $time_end - $time_start; echo "Время выполнения $time секунд\n"; ?> </p>
	  </div>

	  </div>
	</div>
<style>
	.container{
		padding: 50px 0px;
	}
.my_row{
	display: flex;
}
.container h1 {
    text-align: center;
}
.my-col-8 {
    width: 75%;
    height: auto;
}
.my-col-8 p {
    font-size: 1.6em;
}
.my-col-8 p strong {
	color: #138B16;
}
.my-col-2 {
	width: 20%;
}
.my-col-2 h4{
	text-align: center;
}
	.my-col-2 a{
		color: 
	}	
</style>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");