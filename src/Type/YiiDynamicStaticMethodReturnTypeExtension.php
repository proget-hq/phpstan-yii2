<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Type;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

final class YiiDynamicStaticMethodReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return 'Yii';
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'createObject';
    }

    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): Type
    {
        $class = null;
        if ($methodCall->args[0]->value instanceof String_) {
            $class = $methodCall->args[0]->value->value;
        } elseif ($methodCall->args[0]->value instanceof ClassConstFetch) {
            if ($methodCall->args[0]->value->class instanceof Name) {
                $class = $methodCall->args[0]->value->class->toString();
            } elseif ($methodCall->args[0]->value->class instanceof String_) {
                $class = $methodCall->args[0]->value->class->value;
            }
        } elseif ($methodCall->args[0]->value instanceof Array_) {
            $class = $this->extractClassFromArray($methodCall->args[0]->value);
        } elseif ($methodCall->args[0]->value instanceof Closure) {
            $returnType = $methodCall->args[0]->value->getReturnType();
            if ($returnType instanceof Name) {
                return new ObjectType($returnType->toString());
            }

            throw new ShouldNotHappenException('Invalid $type callable argument provided to createObject method - return type is required.');
        }

        if (!$class) {
            throw new ShouldNotHappenException('Invalid $type argument provided to createObject method.');
        }

        return new ObjectType($class);
    }

    private function extractClassFromArray(Array_ $array): string
    {
        $class = null;
        foreach ($array->items as $item) {
            if ($item->key instanceof String_ && $item->key->value === 'class') {
                if (!$item->value instanceof String_) {
                    throw new ShouldNotHappenException('Invalid $type array argument provided to createObject method - "class" key must be a string.');
                }

                if ($class) {
                    throw new ShouldNotHappenException('Invalid $type array argument provided to createObject method - duplicate "class" key.');
                }

                $class = $item->value->value;
            }
        }

        if (!$class) {
            throw new ShouldNotHappenException('Invalid $type array argument provided to createObject method - missing "class" key.');
        }

        return $class;
    }
}
