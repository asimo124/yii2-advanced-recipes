<?php
namespace frontend\controllers;

use yii\rest\ActiveController;

class PatientController extends ActiveController
{
    public $modelClass = 'frontend\models\Patient';
}