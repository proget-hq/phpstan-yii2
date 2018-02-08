<?php
declare(strict_types=1);


namespace Proget\PHPStan\Yii2\Reflection;


use PHPStan\Reflection\ParameterReflection;
use PHPStan\Type\Type;

final class YiiParameterReflection implements ParameterReflection
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $optional;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var bool
     */
    private $passedByReference;

    /**
     * @var bool
     */
    private $variadic;

    public function __construct(
        string $name,
        Type $type,
        bool $optional = false,
        bool $passedByReference = false,
        bool $variadic = false
    )
    {
        $this->name = $name;
        $this->optional = $optional;
        $this->type = $type;
        $this->passedByReference = $passedByReference;
        $this->variadic = $variadic;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function isPassedByReference(): bool
    {
        return $this->passedByReference;
    }

    public function isVariadic(): bool
    {
        return $this->variadic;
    }

}