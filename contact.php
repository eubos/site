<?php


if (isset ($_POST['mail'])) {



  $to = "vyshyvka.kiev@gmail.com";
  $from = $_POST['mail'];
  $subject = "Запрос на вышивку";
  $message = "Имя: ".$_POST['imia']."\nEmail: ".$from."\nIP: ".$_SERVER['REMOTE_ADDR']."\nКомментарий: ".$_POST['koment']."\nТелефон: ".$_POST['telefon']."\nШирина: ".$_POST['shirina']."\nВысота: ".$_POST['visota']."\nКоличество: ".$_POST['kol']."\nСумма: ".$_POST['total']."\nНомер заказа: ".$_POST['nmbzakaz'].$selected_val = "\nТип:".$_POST['tip'];;
  $boundary = md5(date('r', time()));
  $filesize = '';
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "From: " . $from . "\r\n";
  $headers .= "Reply-To: " . $from . "\r\n";
  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
  $message="
Content-Type: multipart/mixed; boundary=\"$boundary\"

--$boundary
Content-Type: text/plain; charset=\"utf-8\"
Content-Transfer-Encoding: 7bit

$message";

  for ($q = 1; $q <= 4; ++$q) {
        $uploadStage = 'fileUpload'.$q;
        $newFileName = "";
        if (!empty($_FILES[$uploadStage])) {
            for ($i = 0; $i < count($_FILES[$uploadStage]['name']); $i++) {
                if (is_uploaded_file($_FILES[$uploadStage]['tmp_name'][$i])) {
                    $attachment = chunk_split(base64_encode(file_get_contents($_FILES[$uploadStage]['tmp_name'][$i])));
                    $filename   = $_FILES[$uploadStage]['name'][$i];
                    $filetype   = $_FILES[$uploadStage]['type'][$i];
                    $filesize += $_FILES[$uploadStage]['size'][$i];
                    $message.="

--$boundary
Content-Type: \"$filetype\"; name=\"$filename\"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename=\"$filename\"

$attachment";

                    $file4copy = $_FILES[$uploadStage]['tmp_name'][0];
                    $filenameArray = explode(".",$_FILES[$uploadStage]['name'][0]);
                    $newFileName   = 'data/images/'.$_POST['nmbzakaz'].'-'.$q.'.'.$filenameArray[count($filenameArray) - 1];
                    $copyRes = copy($file4copy, $newFileName);
                    if (!$copyRes) {
                        echo "не удалось скопировать.";
                        return;
                    }
                }
            }
        }
        $fileNames[] = $newFileName;
    }

    saveSCV($fileNames);

   $message.="
--$boundary--";

  if ($filesize < 30000000) { // проверка на общий размер всех файлов. Многие почтовые сервисы не принимают вложения больше 30 МБ
    $mailResult = mail($to, $subject, $message, $headers);

    if ( $mailResult === true ){
        echo $_POST['imia'].', мы получили Ваш заказ и уже рассматриваем его. Мы свяжемся с Вами в ближайшее время, спасибо! ';
    } else {
        echo "Ошибка отправки данных. Просим связаться при помощих других контактов";
    }
  } else {
    echo 'Извините, письмо не отправлено. Размер всех файлов превышает 20 МБ. Попробуйте еще раз или напишите нам на почту. vyshyvka.kiev@gmail.com';
  }
}

function saveSCV($fileNames = array())
{

    require_once 'parsecsv.lib.php';
    $csv     = new parseCSV();
    $csv->delimiter = ";";
    $csv->delimiter = ";";
    $csvData = array(
        $_POST['imia'],
        $_POST['mail'],
        $_SERVER['REMOTE_ADDR'],
        $_POST['koment'],
        $_POST['telefon'],
        'ширина: '.$_POST['shirina'],
        'высота:'.$_POST['visota'],
        'кол-во: '.$_POST['kol'],
        $_POST['total'],
        $_POST['nmbzakaz'],
        $_POST['tip'],
    );
    $csvData = array_merge($csvData, $fileNames);

    $csv->save('data/orders.csv', array($csvData), ';');
}
?>