<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ReflectionProvider;
use yii\console\Request as ConsoleRequest;
use yii\web\Request as WebRequest;

final class RequestMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{
    /**
     * @var ReflectionProvider
     */
    private $reflectionProvider;

    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if ($classReflection->getName() !== ConsoleRequest::class) {
            return false;
        }

        return $this->reflectionProvider->getClass(WebRequest::class)->hasMethod($methodName);
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        return $this->reflectionProvider->getClass(WebRequest::class)->getNativeMethod($methodName);
    }
}
