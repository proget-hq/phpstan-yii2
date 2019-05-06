<?php

declare(strict_types=1);

namespace Proget\Tests\PHPStan\Yii2\Yii;

/**
 * @property boolean $flag
 */
final class MyActiveRecord extends \yii\db\ActiveRecord
{
    public function getSiblings(): array
    {
        return $this->hasMany(self::class, ['link'])->where(['condition'])->all();
    }

    public function getMaster(): ?self
    {
        return $this->hasOne(self::class, ['link'])->one();
    }

    /**
     * @return self[]
     */
    public function test(): array
    {
        return self::find()->all();
    }
}
