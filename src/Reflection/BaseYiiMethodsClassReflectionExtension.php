<?php
declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Type\ArrayType;
use PHPStan\Type\StringType;

final class BaseYiiMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{
    /**
     * @var string[]
     */
    private $methods = [
        'getVersion',
        'getAlias',
        'getRootAlias',
        'setAlias',
        'autoload',
        'createObject',
        'getLogger',
        'setLogger',
        'trace',
        'error',
        'warning',
        'info',
        'beginProfile',
        'endProfile',
        'powered',
        't',
        'configure',
        'getObjectVars'
    ];

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if($classReflection->getName()!=='Yii') {
            return false;
        }

        return in_array($methodName, $this->methods);
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        if($classReflection->getName()!=='Yii') {
            throw new \InvalidArgumentException(sprintf('Not supported class %s', $classReflection->getName()));
        }

        switch ($methodName) {
            case 'getVersion':
                return new YiiMethodReflection('getVersion', $classReflection, YiiMethodReflection::PUBLIC, true, [], new StringType());
            case 't':
                return new YiiMethodReflection('t', $classReflection, YiiMethodReflection::PUBLIC, true, [
                    new YiiParameterReflection('category', new StringType()),
                    new YiiParameterReflection('message', new StringType()),
                    new YiiParameterReflection('params', new ArrayType(new StringType(), new StringType()), true),
                    new YiiParameterReflection('language', new StringType(), true),
                ], new StringType());
            default:
                throw new \InvalidArgumentException(sprintf('Not supported class %s', $methodName));
        }
    }

}