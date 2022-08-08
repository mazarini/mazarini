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

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Mazarini\ToolBundle\Entity\EntityInterface;

/**
 * @template E of EntityInterface
 */
interface EntityRepositoryInterface extends ServiceEntityRepositoryInterface
{
    /**
     * Return an entity and init the parents if needs.
     *
     * @param int|array<int,int> $ids
     *
     * @return E
     */
    public function getNew(int|array $ids);

    /**
     * Get an entity.
     *
     * @return ?E
     */
    public function get(int $id);

    /**
     * @param E $entity
     */
    public function store(EntityInterface $entity): void;

    /**
     * @param E $entity
     */
    public function remove(EntityInterface $entity): void;

    public function blockFlush(): void;

    public function unblockFlush(): void;
}
