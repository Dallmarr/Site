<?php
include "db_connect.php";
session_start();

if (isset($_POST['login']) && isset($_POST['password'])) {
    if (!empty($_POST['login'])) {
        $login = $_POST['login'];
        $input_password = $_POST['password'];
        $data = $DBH->prepare("SELECT `key` as 'id', `password` FROM `user` WHERE `login` LIKE ?");
        $data->execute([$login]);
        $result = $data->fetchAll();
        $c = $data->rowCount();
        if ($c != 0) {
            $id = $result[0]['id'];
            $password = $result[0]['password'];
            if (password_verify($input_password, $password)) {
                $_SESSION['user_id'] = $id;
            } else {
                $_SESSION['msg_error'] = "Неверный пароль";
                echo "Неверный пароль";
                } //Неверный пароль
        } else {
            echo "Пользователь не существует";
            $_SESSION['msg_error'] = "Пользователь не существует";
        }//Пользователь не существует
    } else {
        echo "Некоректное имя пользователя";
        $_SESSION['msg_error'] = "Некоректное имя пользователя";
        }//Некоректное имя пользователя
}

header("Location: http://cmit-asino.ml/index.php");
