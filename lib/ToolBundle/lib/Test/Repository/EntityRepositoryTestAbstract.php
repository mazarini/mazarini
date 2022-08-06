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

namespace Mazarini\ToolBundle\Test\Repository;

use Doctrine\Bundle\DoctrineBundle\Registry;
use ErrorException;
use Mazarini\ToolBundle\Entity\EntityInterface;
use Mazarini\ToolBundle\Repository\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TypeError;

/**
 * Undocumented class.
 *
 * @template T of EntityInterface
 */
abstract class EntityRepositoryTestAbstract extends KernelTestCase
{
    protected int $id = 0;

    /**
     * @return T
     */
    abstract protected function getNewEntity();

    /**
     * @return EntityRepository<T>
     */
    abstract protected function getRepository();

    protected function setUp(): void
    {
        $entity = $this->getEntity();
        $this->getRepository()->store($entity);
        $this->id = $entity->getId();
    }

    public function testFind(): void
    {
        $entity = $this->getRepository()->find(-1);
        $this->assertNull($entity);

        $entity = $this->getRepository()->find($this->id);
        $this->assertNotNull($entity);
        if (null !== $entity) {
            $this->assertSame($this->id, $entity->getId());
        }
    }

    public function testRemove(): void
    {
        $entity = $this->getRepository()->find($this->id);
        $this->assertNotNull($entity);
        if (null !== $entity) {
            $this->getRepository()->remove($entity);
            $entity = $this->getRepository()->find(1);
            $this->assertNull($entity);
        }
    }

    protected function getRegistry(): Registry
    {
        $registry = static::getContainer()->get('doctrine');
        if (null !== $registry && is_a($registry, Registry::class)) {
            return $registry;
        }
        throw new TypeError();
    }

    /**
     * @return T
     */
    protected function getEntity(int $id = null)
    {
        if (null === $id) {
            return $this->getNewEntity();
        }
        $entity = $this->getRepository()->find($id);
        if (null === $entity) {
            throw new ErrorException(sprintf('Entity with id %d not found', $id));
        }

        return $entity;
    }
}
