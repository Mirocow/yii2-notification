<?php

use yii\user\models\User;
use yii\user\models\Session;

/**
 * @var string $subject
 * @var User $user
 * @var Session $session
 */
?>

<h3><?= $subject ?></h3>

<p>Please use this link to reset your password:</p>

<p><?= Yii::$app->urlManager->createAbsoluteUrl([
  "user/reset",
  "hash" => $user->hash, 
  "sid" => $session->sid]); ?></p>