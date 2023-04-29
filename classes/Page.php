<?php

class Page
{
    static function display_header($title)
    { ?>
        <html lang="en-GB">
        <head>
            <title><?php echo $title ?></title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- <link rel="stylesheet" href="style.css" type="text/css" /> -->
        </head>
        <body>
        <?php
    }

    static function display_navigation()
    { ?>
        <a href="index.php">index</a><br>
        <a href="messages.php">messages</a><br>
        <a href="message_add.php">add new message</a><br>
        <?php
    }

    static function display_sesion()
    {
        $Pdo = new Pdo_();
        if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'niezalogowany' && $_SERVER['REQUEST_URI'] != "/testic3/index.php") {
            header("Location: http://localhost/testic3/index.php");
        }
        if (isset($_SESSION['login']) && $_SESSION['logged'] == 'YES') {
            echo "jestes zalogowany jako: " . $_SESSION['login'] . '<BR/>';
            //if sesja nie wygasła
            if (isset($_SESSION['expire_time']) && $_SESSION['expire_time'] > time()) {
                //dodaj czas do sesji
                $_SESSION['expire_time'] = time() + 300;
                //wyświetl dane
                echo "expire_time: " . $_SESSION['expire_time'] . '<BR/>';
                echo "czas: " . time() . '<BR/>';
            } else {
                $Pdo->wyloguj();
                session_destroy();
            }
        } else {
            echo "Jestes niezalogowany!<br>";

        }
    }
}

