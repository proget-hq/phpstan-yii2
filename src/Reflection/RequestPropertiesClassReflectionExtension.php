<?php
declare(strict_types=1);


namespace Proget\PHPStan\Yii2\Reflection;


use PhpParser\PrettyPrinter\Standard;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;

final class RequestPropertiesClassReflectionExtension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    /**
     * @var Broker
     */
    private $broker;

    public function setBroker(Broker $broker)
    {
        $this->broker = $broker;
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if($classReflection->getName()!=='yii\console\Request') {
            return false;
        }

        $webRequest = $this->broker->getClass('yii\web\Request');

        return $webRequest->hasProperty($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        $webRequest = $this->broker->getClass('yii\web\Request');

        $printer = new Standard();

        return $webRequest->getProperty($propertyName, new Scope(
            $this->broker,
            $printer,
            new TypeSpecifier($printer),
            (string) $classReflection->getFileName()
        ));
    }

}