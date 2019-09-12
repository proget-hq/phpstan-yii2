<?php

declare(strict_types=1);

namespace Proget\Tests\PHPStan\Yii2\Yii;

final class MyController extends \yii\web\Controller
{
    public function actionMy(): void
    {
        $record = MyActiveRecord::find()->where(['flag' => \Yii::$app->request->post('flag', true)])->one();
        if ($record) {
            $record->flag = false;
            $record->save();
        }

        $record = MyActiveRecord::findOne(['condition']);
        if ($record) {
            $record->flag;
        }

        $records = MyActiveRecord::find()->asArray()->where(['flag' => \Yii::$app->request->post('flag', true)])->all();
        foreach ($records as $record) {
            $record['flag'];
        }

        $records = MyActiveRecord::findAll('condition');
        foreach ($records as $record) {
            $record->flag;
        }

        $records = MyActiveRecord::find()->asArray(false)->where(['condition'])->all();
        foreach ($records as $record) {
            $record->flag;
            $record['flag'];
        }

        \Yii::$app->response->data = \Yii::$app->request->rawBody;

        \Yii::$app->user->isGuest;
        \Yii::$app->user->identity->getAuthKey();
        \Yii::$app->user->identity->doSomething();

        \Yii::$app->customComponent->flag;

        \Yii::createObject(\SplObjectStorage::class)->count();
        \Yii::createObject('SplObjectStorage')->count();
        \Yii::createObject(['class' => '\SplObjectStorage'])->count();
        \Yii::createObject(static function (): \SplObjectStorage {
            return new \SplObjectStorage();
        })->count();

        (int)\Yii::$app->request->headers->get('Content-Length');
        (int)\Yii::$app->request->headers->get('Content-Length', 0, true);
        $values = \Yii::$app->request->headers->get('X-Key', '', false);
        reset($values);
    }
}
