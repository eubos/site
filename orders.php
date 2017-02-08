<?php
session_start();

if (isset($_GET['logout'])) {
    unset($_SESSION['auth']);
    session_unregister('auth');
}

function checkAuth()
{
    if (!empty($_SESSION['auth']) && $_SESSION['auth'] == md5(date("Ym"))) {
        return true;
    }

    if (isset($_POST['inputEmail'])) {
        if ($_POST['inputEmail'] == "vyshyvka.kiev@gmail.com" &&
            $_POST['inputPassword'] == "aewinnka&oew39ga") {
            $_SESSION['auth'] = md5(date("Ym"));
            return true;
        }
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Компьютерная | Машинная вышивка на заказ: шевроны, нашивки, логотипы Киев</title>
        <meta name="description" lang="ru" content="Вышивка №1 в городе Киев: ❶ Вышивка на ткани ❷ Изготовление нашивок/шевронов ❸Вышивка логотипов на корпоративной одежде ❹Мы Вас удивим! " />
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            *,html {
                font-size: 12px;
            }
            img.img-preview {
                max-height: 100px;
                max-width: 100px;
            }
        </style>
    </head>
    <body>


        <div class="container">
            <?php if (checkAuth() !== TRUE) { ?>
                <form class="form-signin" action="orders.php" method="POST">
                    <h2 class="form-signin-heading">Please sign in</h2>
                    <label for="inputEmail" class="sr-only">Email address</label>
                    <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                </form>
            <?php } else {
                ?>

                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">VYSHYVKA.LTD.UA</a>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li class="active"><a href="#">Home</a></li>
                            </ul>

                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="orders.php?logout">logout</a></li>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div><!--/.container-fluid -->
                </nav>


                <?php
                $fields = array(
                    "Имя",
                    "Email",
                    "IP",
                    "Комментарий",
                    "Телефон",
                    "Ширина(см.)",
                    "Высота(см.)",
                    "Количество(шт.)",
                    "Сумма",
                    "Номер заказа",
                    "Тип",
                    "картинки",
                    "Статус"
                );

                require_once 'parsecsv.lib.php';
                $csv = new parseCSV();
                $csv->delimiter = ";";
                $csv->parse('data/orders.csv');
                ?>
                <div class="table">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <?php
                                foreach ($fields as $field) {
                                    echo "<th>$field</th>";
                                }
                                ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($csv->data)) {
                                foreach ($csv->data as $row) {
                                    echo "<tr>";
                                    foreach (array_values($row) as $colId => $col) {
                                        switch ($colId){
                                            case 11:
                                                echo "<td>";
                                                if ( $col != "" ){
                                                    echo "<a href='$col' target=_blank><img src=$col class='img-preview'></a>";
                                                }
                                                break;
                                            case 12:
                                            case 13:
                                                if ( $col != "" ){
                                                    echo "<a href='$col' target=_blank><img src=$col class='img-preview'></a>";
                                                }
                                                break;
                                            case 14:
                                                if ( $col != "" ){
                                                    echo "<a href='$col' target=_blank><img src=$col class='img-preview'></a>";
                                                }
                                                echo "</td>";
                                                break;
                                            default:
                                                echo "<td>$col</td>";
                                                break;
                                        }

                                    }
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

        </div>
        <!-- /container -->

        <script>
            $(document).ready(function(){
                $('.img-preview').on('click',function(){
                    
                });
            });
        </script>
    </body>