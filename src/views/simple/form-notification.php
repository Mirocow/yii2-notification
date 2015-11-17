<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var mirocow\notification\models\SimpleNotificationForm $model
 * @var ActiveForm $form
 */
?>

<h3>Спам</h3>

<div class="notification-form-notification">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'to')->textInput() ?>

    <?= $form->field($model, 'subject')->textInput() ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 23]) ?>

    <?php if($errors):?>
      Лог:<br>
      <pre>
      <?= implode("\n", $errors);?>
      </pre>
    <?php endif;?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div><!-- notification-form-notification -->