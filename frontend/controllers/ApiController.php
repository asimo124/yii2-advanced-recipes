<?php
namespace frontend\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use common\models\User;

class ApiController extends Controller
{

    public $collectionOptions = ['GET', 'POST', 'OPTIONS'];
    public $resourceOptions = ['GET', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            /*/
            'class' => CompositeAuth::className(),
            'authMethods' => [
                'basicAuth' => [
                    'class' => HttpBasicAuth::className(),
                    'auth' => function ($username, $password) {
                        return User::validateBasicAuth($username,$password);
                    },
                ],
                'bearerAuth' => [
                    'class' => HttpBearerAuth::className(),
                ]
            ],
            //*/
            'class' => \yii\filters\auth\HttpBearerAuth::className(),

        ];

        // remove authentication filter to add CORS first
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    public function actionOptions($id = null) {
        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
        }
        $options = $id === null ? $this->collectionOptions : $this->resourceOptions;
        $headers = Yii::$app->getResponse()->getHeaders();
        $headers->set('Allow', implode(', ', $options));
        $headers->set('Access-Control-Allow-Methods', implode(', ', $options));
    }

}
