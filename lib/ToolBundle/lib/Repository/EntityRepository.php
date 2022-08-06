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
 * @template T of EntityInterface
 * @template-extends ServiceEntityRepository<T>
 *
 * @method T|null find($id, $lockMode = null, $lockVersion = null)
 * @method T|null findOneBy(array $criteria, array $orderBy = null)
 * @method T[]    findAll()
 * @method T[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityRepository extends ServiceEntityRepository
{
    public static bool $autoFlush = true;
    public static int $qtyToFlush = 0;

    public function groupFlush(): void
    {
        static::$autoFlush = true;
    }

    public function setAutoflush(): void
    {
        static::$autoFlush = false;
    }

    public function store(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
        ++static::$qtyToFlush;
        if (static::$autoFlush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(object $entity): void
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
