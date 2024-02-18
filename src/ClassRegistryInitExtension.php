<?php

declare(strict_types=1);

namespace PHPStanCakePHP2;

use PHPStan\Type\Constant\ConstantStringType;
use PHPStanCakePHP2\CakePHP\PortedInflector;
use PHPStanCakePHP2\Service\SchemaService;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * @see \PHPStanCakePHP2\Test\Feature\ClassRegistryInitTest
 */
final class ClassRegistryInitExtension implements DynamicStaticMethodReturnTypeExtension
{
    private ReflectionProvider $reflectionProvider;

    private SchemaService $schemaService;

    public function __construct(
        ReflectionProvider $reflectionProvider,
        SchemaService $schemaService
    ) {
        $this->reflectionProvider = $reflectionProvider;
        $this->schemaService = $schemaService;
    }

    public function getClass(): string
    {
        return 'ClassRegistry';
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'init';
    }

    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): ?Type
    {
        $argumentType = $scope->getType($methodCall->getArgs()[0]->value);

        if (!$argumentType instanceof ConstantStringType) {
            return $this->getDefaultType();
        }

        $value = $argumentType->getValue();

        if ($this->reflectionProvider->hasClass($value)) {
            return new ObjectType($value);
        }

<<<<<<< HEAD
<<<<<<< HEAD:src/ClassRegistryInitExtension.php
        if ($this->schemaService->hasTable(PortedInflector::tableize($value))) {
=======
        $tableName = PortedInflector::tableize($value);
        if ($this->schemaService->hasTable($tableName)) {
>>>>>>> e0614d1 (fixup! misc):src/ReturnTypeExtension/ClassRegistryInitExtension.php
=======
        $tableName = PortedInflector::tableize($value);

        var_dump($tableName);

        if ($this->schemaService->hasTable($tableName)) {
>>>>>>> 7e16f7f (tidy up)
            return new ObjectType('Model');
        }

        return $this->getDefaultType();
    }

    private function getDefaultType(): Type
    {
        return new UnionType([
            new BooleanType(),
            new ObjectWithoutClassType(),
        ]);
    }
}
