<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Type\Type;

final class ComponentPropertyReflection implements PropertyReflection
{
    /**
     * @var PropertyReflection
     */
    private $fallbackProperty;

    /**
     * @var Type
     */
    private $type;

    public function __construct(PropertyReflection $fallbackProperty, Type $type)
    {
        $this->fallbackProperty = $fallbackProperty;
        $this->type = $type;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function isReadable(): bool
    {
        return $this->fallbackProperty->isReadable();
    }

    public function isWritable(): bool
    {
        return $this->fallbackProperty->isWritable();
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->fallbackProperty->getDeclaringClass();
    }

    public function isStatic(): bool
    {
        return $this->fallbackProperty->isStatic();
    }

    public function isPrivate(): bool
    {
        return $this->fallbackProperty->isPrivate();
    }

    public function isPublic(): bool
    {
        return $this->fallbackProperty->isPublic();
    }
}
