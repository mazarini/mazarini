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

namespace AppToolBundle\Tests\Repository;

use App\Entity\Entity;
use App\Repository\EntityRepository;
use Mazarini\ToolBundle\Test\Repository\EntityRepositoryTestAbstract;
use TypeError;

/**
 * @extends EntityRepositoryTestAbstract<Entity>
 */
class EntityRepositoryTest extends EntityRepositoryTestAbstract
{
    /**
     * @return Entity
     */
    protected function getNewEntity()
    {
        return new Entity();
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        $repository = $this->getRegistry()->getRepository(Entity::class);
        if (is_a($repository, EntityRepository::class)) {
            return $repository;
        }
        throw new TypeError();
    }
}
