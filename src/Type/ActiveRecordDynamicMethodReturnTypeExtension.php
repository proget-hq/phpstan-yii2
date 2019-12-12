<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Type;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;

final class ActiveRecordDynamicMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return 'yii\db\ActiveRecord';
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return \in_array($methodReflection->getName(), ['hasOne', 'hasMany'], true);
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $argType = $scope->getType($methodCall->args[0]->value);
        if (!$argType instanceof ConstantStringType) {
            throw new ShouldNotHappenException(sprintf('Invalid argument provided to method %s'.PHP_EOL.'Hint: You should use ::class instead of ::className()', $methodReflection->getName()));
        }

        return new ActiveQueryObjectType($argType->getValue(), false);
    }
}
