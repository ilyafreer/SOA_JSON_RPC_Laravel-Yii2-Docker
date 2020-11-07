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

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->client = new JsonRpcClient();
        $this->enableCsrfValidation = false;
        $this->view->registerJsFile(Yii::getAlias('@web/js/api.js'), ['depends' => JqueryAsset::className()]);
    }

    /**
     * @return array|array[]
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'send' => ['POST'],
                    '*' => ['GET'],
                ],
            ],
        ];
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
        ];
    }

    /**
     * Главный метод отправки данных на SOA сервер
     * @return array|bool[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionSend()
    {
        $answer = $this->client->defaultErrorAnswer();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (Yii::$app->request->validateCsrfToken($data['token'])) {
                $response = $this->client->send($data['method'], $data['params']);
                if (!empty($response['result'])) {
                    $answer = $response;
                }
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
        }
        $response = str_replace('{token}', Yii::$app->request->getCsrfToken(), $response['result']['data']);
        return $this->render('one', ['response' => $response]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionTwo()
    {
        $responseOne = $this->client->send('getCasinoBanner', ['casino_id' => 777]);
        if (empty($responseOne['result'])) {
            // данные не получили, сценарий не предусмотрен по ТЗ для примера кидаю 404
            throw new NotFoundHttpException(404);
        }
        $responseTwo = $this->client->send('getFormTwo', ['casino_id' => 777]);
        if (empty($responseTwo['result'])) {
            // данные не получили, сценарий не предусмотрен по ТЗ для примера кидаю 404
            throw new NotFoundHttpException(404);
        }

        $response = str_replace('{token}', Yii::$app->request->getCsrfToken(), $responseOne['result']['data']);
        $response .= str_replace('{token}', Yii::$app->request->getCsrfToken(), $responseTwo['result']['data']);
        return $this->render('one', ['response' => $response]);
    }
}
