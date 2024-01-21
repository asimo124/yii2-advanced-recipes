<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;

class RiRecipeController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    // gets all districts visible to the logged in user
    public function actionIndex()
    {
        header("Access-Control-Allow-Origin: *");
        echo json_encode(print_r([
            "success" => true
        ]), true);
        die();
    }

}
