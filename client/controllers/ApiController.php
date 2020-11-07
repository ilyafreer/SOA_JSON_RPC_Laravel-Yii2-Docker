<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\logic\JsonRpcClient,
    yii\web\NotFoundHttpException,
    yii\web\JqueryAsset;

class ApiController extends Controller
{
    /**
     * @var JsonRpcClient
     */
    private $client;

    public function init()
    {
        parent::init();
        $this->client = new JsonRpcClient();
        $this->enableCsrfValidation = false;
        $this->view->registerJsFile(Yii::getAlias('@web/js/api.js'), ['depends' => JqueryAsset::className()]);
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

    /**
     * Главный метод отправки данных на SOA сервер
     * @return array|bool[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionSend()
    {
        $answer =  $this->client->defaultErrorAnswer();
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $response = $this->client->send($data['method'], $data['params']);

            if (!empty($response['result'])) {
                $answer = $response;
            }
        }
        return json_encode($answer);
    }

    /**
     * Главная страница
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Отправляет запрос получение формы для страницы
     *
     * @return string
     */
    public function actionOne()
    {
        $response = $this->client->send('getCasinoBanner', ['casino_id' => 777]);
        if (empty($response['result'])) {
            // данные не получили, сценарий не предусмотрен по ТЗ для примера кидаю 404
            throw new NotFoundHttpException(404);
        }else{
            return $this->render('one',['response'=>$response['result']['data']]);
        }
    }


    public function actionTwo()
    {
        $response = $this->client->send('getCasinoBanner', ['casino_id' => 777]);

        if (empty($response['result'])) {
            // данные не получили, сценарий не предусмотрен по ТЗ для примера кидаю 404
            throw new NotFoundHttpException(404);
        }else{
            return $this->render('two',$response);
        }
    }
}
