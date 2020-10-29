<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class BaseController extends Controller
{
    public function setSuccess($message)
    {
        Yii::$app->session->setFlash("success", $message);
    }

    public function setWarning($message)
    {
        Yii::$app->session->setFlash("warning", $message);
    }

    public function setError($message)
    {
        Yii::$app->session->setFlash("danger", $message);
    }
}
