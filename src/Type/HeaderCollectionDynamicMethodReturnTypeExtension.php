<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Type;

use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use yii\web\HeaderCollection;

class HeaderCollectionDynamicMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return HeaderCollection::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'get';
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        if (count($methodCall->args) < 3) {
            // $first === true (the default) and the get-method returns something of type string
            return new StringType();
        }

        $val = $methodCall->args[2]->value;
        if ($val instanceof ConstFetch) {
            $value = $val->name->parts[0];
            if ($value === 'true') {
                // $first === true, therefore string
                return new StringType();
            }

            if ($value === 'false') {
                // $first === false, therefore string[]
                return new ArrayType(new IntegerType(), new StringType());
            }
        }

        // Unable to figure out value of third parameter $first, therefore it can be of either type
        return new UnionType([new ArrayType(new IntegerType(), new StringType()), new StringType()]);
    }
}
