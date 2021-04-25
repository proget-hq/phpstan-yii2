<?php

declare(strict_types=1);

namespace Proget\Tests\PHPStan\Yii2\Yii;

final class MyController extends \yii\web\Controller
{
    public function actionMy(): void
    {
        $offsetProp = 'flag';
        $flag = false;
        $record = MyActiveRecord::find()->where(['flag' => \Yii::$app->request->post('flag', true)])->one();
        if ($record) {
            $record->flag = false;
            $flag = $record[$offsetProp];
            $record[$offsetProp] = true;
            $record->save();
        }

        $record = MyActiveRecord::findOne(['condition']);
        if ($record) {
            $flag = $record->flag;
            $flag = $record['flag'];
        }

        $records = MyActiveRecord::find()->asArray()->where(['flag' => \Yii::$app->request->post('flag', true)])->all();
        foreach ($records as $record) {
            $flag = $record['flag'];
        }

        $records = MyActiveRecord::findAll('condition');
        foreach ($records as $record) {
            $flag = $record->flag;
        }

        $records = MyActiveRecord::find()->asArray(false)->where(['condition'])->all();
        foreach ($records as $record) {
            $flag = $record->flag;
            $flag = $record['flag'];
            $record['flag'] = true;
        }

        \Yii::$app->response->data = \Yii::$app->request->rawBody;

        $guest = \Yii::$app->user->isGuest;
        \Yii::$app->user->identity->getAuthKey();
        \Yii::$app->user->identity->doSomething();

        $flag = \Yii::$app->customComponent->flag;

        $objectClass = \SplObjectStorage::class;
        \Yii::createObject($objectClass)->count();
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
