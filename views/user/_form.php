<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>    

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, "disabled" => $model->username]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'admin' => 'Admin', 'user' => 'User', ], ['prompt' => '', "disabled" => Yii::$app->user->getId() == $model->id]) ?>

    <?= $form->field($model, 'status')->checkbox(["checked" => $model->getStatus(), "disabled" => Yii::$app->user->getId() == $model->id ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
