<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Type;

use PHPStan\TrinaryLogic;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ErrorType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class ActiveRecordObjectType extends ObjectType
{
    public function hasOffsetValueType(Type $offsetType): TrinaryLogic
    {
        if (!$offsetType instanceof ConstantStringType) {
            return TrinaryLogic::createNo();
        }

        if ($this->isInstanceOf(\ArrayAccess::class)->yes()) {
            return TrinaryLogic::createFromBoolean($this->hasProperty($offsetType->getValue())->yes());
        }

        return parent::hasOffsetValueType($offsetType);
    }

    public function setOffsetValueType(?Type $offsetType, Type $valueType, bool $unionValues = true): Type
    {
        if ($offsetType instanceof ConstantStringType && $this->hasProperty($offsetType->getValue())->no()) {
            return new ErrorType();
        }

        return $this;
    }
}
