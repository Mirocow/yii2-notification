<?php

$i = 1;

//use yii\user\models\User;
//use yii\user\models\Profile;
//use yii\user\models\Session;

/**
 * @var string $subject
 * @var User $user
 * @var Profile $profile
 * @var Session $session
 */
?>

    <h3><? //= $subject ?></h3>

    Администратор сайта <?= Yii::$app->urlManager->createAbsoluteUrl("/"); ?> создал для вас
    <br>
    аккаунт. Можете войти на сайт, кликнув на<br>
    ссылку или скопировав и вставив её в<br>
    адресную строку браузера:

    <p><?= Yii::$app->urlManager->createAbsoluteUrl([
      "user/confirm",
      "hash" => $user->hash, 
      "sid" => $session->sid]); ?></p>

    Эта одноразовая ссылка для входа на сайт<br>
    направит вас на страницу задания своего<br>
    пароля.<br>

    После установки пароля вы сможете входить<br>
    на сайт через страницу <?= Yii::$app->urlManager->createAbsoluteUrl("user/confirm"); ?>
    <br>
    со следующими данными:

    пользователь: <?= $user->email ?><br>
    пароль: <?= $user->newPassword ?>