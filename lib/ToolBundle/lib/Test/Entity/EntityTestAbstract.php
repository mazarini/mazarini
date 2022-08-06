<?php

/*
 * This file is part of mazarini/mazarini.
 *
 * mazarini/mazarini is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * mazarini/mazarini is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with mazarini/mazarini. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Mazarini\ToolBundle\Test\Entity;

use Mazarini\ToolBundle\Entity\EntityInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @template T of EntityInterface
 */
abstract class EntityTestAbstract extends TestCase
{
    /**
     * @return T
     */
    abstract protected function getNewEntity();

    /**
     * @return array<int, array<int, int|string|object>>
     */
    abstract public function getGetterSetterData(): array;

    public function testIsNew(): void
    {
        $entity = $this->getNewEntity();
        $this->assertTrue($entity->isNew());
        $this->assertSame(0, $entity->getId());

        $entity = $this->getEntity(1);
        $this->assertFalse($entity->isNew());
        $this->assertSame(1, $entity->getId());
    }

    /**
     * Test getters and setters of an object.
     *
     * @param string|int|object $value
     * @param string|int|object $default
     *
     * @dataProvider getGetterSetterData
     */
    public function testGetterSetter(string $property, $default, $value): void
    {
        $setter = 'set'.ucfirst($property);
        $getter = 'get'.ucfirst($property);
        $entity = $this->getNewEntity();
        $this->assertTrue(method_exists($entity, $getter));
        $this->assertTrue(method_exists($entity, $getter));
        $this->assertSame($default, $entity->$getter());
        $this->assertSame($entity, $entity->$setter($value));
        $this->assertSame($value, $entity->$getter());
    }

    /**
     * @return T
     */
    protected function getEntity(int $id = null)
    {
        $entity = $this->getNewEntity();
        $reflectionClass = new ReflectionClass($entity);
        $reflectionClass->getProperty('id')->setValue($entity, $id);

        return $entity;
    }
}
