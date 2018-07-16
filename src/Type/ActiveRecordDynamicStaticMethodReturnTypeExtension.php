<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Type;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

final class ActiveRecordDynamicStaticMethodReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return 'yii\db\ActiveRecord';
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'findOne';
    }

    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): Type
    {
        /** @var Name $name */
        $name = $methodCall->class;

        return TypeCombinator::union(
            new NullType(), new ObjectType($name->toString())
        );
    }
}
