<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Type;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

final class ActiveQueryDynamicMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return 'yii\db\ActiveQuery';
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        if (ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType() instanceof ThisType) {
            return true;
        }

        return \in_array($methodReflection->getName(), ['one', 'all'], true);
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $calledOnType = $scope->getType($methodCall->var);
        if (!$calledOnType instanceof ActiveQueryObjectType) {
            throw new ShouldNotHappenException(sprintf('Unexpected type %s during method call %s at line %d', \get_class($calledOnType), $methodReflection->getName(), $methodCall->getLine()));
        }

        $methodName = $methodReflection->getName();
        if ($methodName === 'asArray') {
            $argType = isset($methodCall->args[0]) ? $scope->getType($methodCall->args[0]->value) : new ConstantBooleanType(true);
            if (!$argType instanceof ConstantBooleanType) {
                throw new ShouldNotHappenException(sprintf('Invalid argument provided to asArray method at line %d', $methodCall->getLine()));
            }

            return new ActiveQueryObjectType($calledOnType->getModelClass(), $argType->getValue());
        }

        if (!\in_array($methodName, ['one', 'all'], true)) {
            return new ActiveQueryObjectType($calledOnType->getModelClass(), $calledOnType->isAsArray());
        }

        if ($methodName === 'one') {
            return TypeCombinator::union(
                new NullType(),
                $calledOnType->isAsArray() ? new ArrayType(new StringType(), new MixedType()) : new ObjectType($calledOnType->getModelClass())
            );
        }

        return new ArrayType(
            new IntegerType(),
            $calledOnType->isAsArray() ? new ArrayType(new StringType(), new MixedType()) : new ObjectType($calledOnType->getModelClass())
        );
    }
}
