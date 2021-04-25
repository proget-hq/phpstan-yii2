<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Type;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

final class ActiveRecordDynamicStaticMethodReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return ActiveRecord::class;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        $returnType = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();
        if ($returnType instanceof ThisType) {
            return true;
        }

        if ($returnType instanceof UnionType) {
            foreach ($returnType->getTypes() as $type) {
                if ($type instanceof ObjectType) {
                    return \is_a($type->getClassName(), $this->getClass(), true);
                }
            }
        }

        return $returnType instanceof ObjectType && \is_a($returnType->getClassName(), ActiveQuery::class, true);
    }

    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): Type
    {
        /** @var Name $className */
        $className = $methodCall->class;
        $name = $scope->resolveName($className);

        $returnType = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();
        if ($returnType instanceof ThisType) {
            return new ActiveRecordObjectType($name);
        }

        if ($returnType instanceof UnionType) {
            return TypeCombinator::union(
                new NullType(),
                new ActiveRecordObjectType($name)
            );
        }

        return new ActiveQueryObjectType($name, false);
    }
}
