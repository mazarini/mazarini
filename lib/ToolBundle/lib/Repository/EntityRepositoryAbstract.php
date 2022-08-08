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

namespace Mazarini\ToolBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Mazarini\ToolBundle\Entity\EntityInterface;

/**
 * @template E of EntityInterface
 * @template-extends ServiceEntityRepository<E>
 * @template-implements EntityRepositoryInterface<E>
 *
 * @method E|null find($id, $lockMode = null, $lockVersion = null)
 * @method E|null findOneBy(array $criteria, array $orderBy = null)
 * @method E[]    findAll()
 * @method E[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
abstract class EntityRepositoryAbstract extends ServiceEntityRepository implements EntityRepositoryInterface
{
    public static bool $autoFlush = true;
    public static int $qtyToFlush = 0;

    /**
     * Undocumented function.
     *
     * @param int|array<int,int> $id
     *
     * @return E
     */
    abstract public function getNew($id = 0);

    /**
     * Undocumented function.
     *
     * @return ?E
     */
    public function get(int $id)
    {
        return $this->find($id);
    }

    public function blockFlush(): void
    {
        static::$autoFlush = false;
    }

    public function unblockFlush(): void
    {
        static::$autoFlush = true;
    }

    /**
     * Save an entity.
     *
     * @param E $entity
     */
    public function store($entity): void
    {
        $this->getEntityManager()->persist($entity);
        ++static::$qtyToFlush;
        if (static::$autoFlush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove an entity.
     *
     * @param E $entity
     */
    public function remove($entity): void
    {
        ++static::$qtyToFlush;
        $this->getEntityManager()->remove($entity);

        if (static::$autoFlush) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
        static::$qtyToFlush = 0;
    }
}
