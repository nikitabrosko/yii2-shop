<?php

namespace frontend\controllers\payment;

use robokassa\FailAction;
use robokassa\Merchant;
use robokassa\ResultAction;
use robokassa\SuccessAction;
use shop\entities\shop\order\Order;
use shop\readModels\shop\OrderReadRepository;
use shop\services\shop\OrderService;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RobokassaController extends Controller
{
    public $layout = 'cabinet';

    private $orders;
    private $orderService;

    public function __construct($id, $module, OrderReadRepository $orders, OrderService $orderService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->orders = $orders;
        $this->orderService = $orderService;
    }

    public function actionInvoice($id)
    {
        $order = $this->loadModel($id);

        return $this->getMerchant()->payment($order->cost, $order->id, 'Payment', null, $order->user->email);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'result' => [
                'class' => ResultAction::class,
                'callback' => [$this, 'resultCallback'],
            ],
            'success' => [
                'class' => SuccessAction::class,
                'callback' => [$this, 'successCallback'],
            ],
            'fail' => [
                'class' => FailAction::class,
                'callback' => [$this, 'failCallback'],
            ],
        ];
    }

    /**
     * Переадресация пользователя при успешной оплате на SuccessURL.
     * Переход пользователя по данному адресу означает, что оплата Вашего заказа успешно выполнена.
     * Однако для дополнительной защиты желательно, чтобы факт оплаты проверялся скриптом, исполняемым при переходе на ResultURL
     */
    public function successCallback($merchant, $options): \yii\web\Response
    {
        return $this->goBack();
    }

    /**
     * Оповещение об оплате на ResultURL
     * ResultURL предназначен для получения Вашим сайтом оповещения об успешном платеже в автоматическом режиме.
     * В случае успешного проведения оплаты ROBOKASSA делает запрос на ResultURL
     * Ваш скрипт работает правильно и повторное уведомление с нашей стороны не требуется.
     * Результат должен содержать  текст OK и параметр InvId.
     * Например, для номера счёта 5 должен быть возвращён вот такой ответ: OK5.
     */
    public function resultCallback($merchant, $options): string
    {
        $order = $this->loadModel($options->invId);

        try {
            $this->orderService->pay($order->id);

            return 'OK' . $options->invId;
        } catch (\DomainException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Переадресация пользователя при отказе от оплаты на FailURL
     * В случае отказа от исполнения платежа Покупатель перенаправляется по данному адресу.
     * Необходимо для того, чтобы Продавец мог, например, разблокировать заказанный товар на складе.
     * Переход пользователя по данному адресу, строго говоря, не означает окончательного отказа
     * Покупателя от оплаты, нажав кнопку «Back» в браузере он может вернуться на страницы ROBOKASSA.
     */
    public function failCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        $order = $this->loadModel($nInvId);
        $this->orderService->fail($order->id);
    }

    private function loadModel($id)
    {
        if (!$order = $this->orders->findOwn(\Yii::$app->user->id, $id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $order;
    }

    protected function getMerchant() : Merchant
    {
        return Yii::$app->get('robokassa');
    }
}