<?php
declare(strict_types=1);


namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Type\Type;

final class YiiMethodReflection implements MethodReflection
{
    public const PUBLIC = 'public';
    public const PRIVATE = 'private';

    /**
     * @var string
     */
    private $name;

    /**
     * @var ClassReflection
     */
    private $declaringClass;

    /**
     * @var string;
     */
    private $visibility;

    /**
     * @var bool
     */
    private $static;

    /**
     * @var ParameterReflection[]
     */
    private $parameters;

    /**
     * @var Type
     */
    private $returnType;

    public function __construct(
        string $name,
        ClassReflection $declaringClass,
        string $visibility,
        bool $static,
        array $parameters,
        Type $returnType
    )
    {
        $this->name = $name;
        $this->declaringClass = $declaringClass;
        $this->visibility = $visibility;
        $this->static = $static;
        $this->parameters = $parameters;
        $this->returnType = $returnType;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    public function isStatic(): bool
    {
        return $this->static;
    }

    public function isPrivate(): bool
    {
        return $this->visibility === self::PRIVATE;
    }

    public function isPublic(): bool
    {
        return $this->visibility === self::PUBLIC;
    }

    public function getPrototype(): MethodReflection
    {
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function isVariadic(): bool
    {
        return false;
    }

    public function getReturnType(): Type
    {
        return $this->returnType;
    }

}