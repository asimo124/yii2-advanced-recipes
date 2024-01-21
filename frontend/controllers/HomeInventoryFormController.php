<?php

namespace frontend\controllers;

use frontend\models\Item;
use frontend\models\ItemUsedHistory;
use frontend\models\RiDifficultyLevel;
use frontend\models\RiHomeInventory;
use frontend\models\RiIngredient;
use frontend\models\RiIngredientType;
use frontend\models\RiProtein;
use frontend\models\RiRecipe;
use frontend\models\RiRecipeIngredient;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\LoginForm;
use frontend\ContactForm;

class HomeInventoryFormController extends ApiController
{
    public $enableCsrfValidation = false;
    //*/
    private $allowedOriginDomain = "https://recipes.hawleywebdesign.com";
    /*/
    private $allowedOriginDomain = "http://localhost:4200";
    //*/

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => [
                        'home-inventory',
                        'remove-ingredient',
                        'update-recipe-ingredients'
                    ],
                    'allow' => true,
                    'roles' => ['@']
                ]
            ],
        ];

        //$behaviors['authenticator']['except'] = ['top-recipes'];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionHomeInventory()
    {
        $HomeInventoryItems2 = RiHomeInventory::find()
            ->joinWith(['ingredient'])
            ->orderBy(['ri_ingredient.title' => SORT_ASC])
            ->asArray()
            ->all();

        $HomeInventoryItems = [];
        foreach ($HomeInventoryItems2 as $getItem) {
            $HomeInventoryItems[] = $getItem['ingredient'];
        }

        header("Access-Control-Allow-Origin: {$this->allowedOriginDomain}");
        header("Content-type: application/json");

        echo json_encode([
            "items" => $HomeInventoryItems
        ]);
        die();
    }

    public function actionRemoveIngredient() {

        $request = Yii::$app->request;

        $ingredientId = $request->post("ingredient_id", 0);

        if ($ingredientId) {
            $riRecipeIngred = RiHomeInventory::find()
                ->where([
                    'ingredient_id' => $ingredientId,
                ])
                ->one();
            $riRecipeIngred->delete();
        }

        header("Access-Control-Allow-Origin: {$this->allowedOriginDomain}");
        header("Content-type: application/json");

        echo json_encode([
            "success" => true
        ]);
        die();
    }

    public function actionUpdateRecipeIngredients()
    {
        $request = Yii::$app->request;

        $newIngredientsArr2 = $request->post("ingredients", []);

        $newIngredientsArr = [];
        foreach ($newIngredientsArr2 as $key => $value) {
            $newIngredientsArr[] = $value;
        }

        if (count($newIngredientsArr) == 0) {
            throw new \yii\web\NotFoundHttpException('Did not pass ingredient ids to update', true);
        }

        foreach ($newIngredientsArr as $getIngredId) {

            $ingredExists = RiHomeInventory::find()
                ->where([
                    'ingredient_id' => $getIngredId
                ])
                ->asArray()
                ->one();

            if (!$ingredExists) {

                $recipeIngredient = new RiHomeInventory();
                $recipeIngredient->ingredient_id = $getIngredId;
                $recipeIngredient->save();
            }
        }

        header("Access-Control-Allow-Origin: {$this->allowedOriginDomain}");
        header("Content-type: application/json");

        echo json_encode([
            "success" => true
        ]);
        die();
    }
}
