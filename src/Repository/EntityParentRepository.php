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

namespace App\Repository;

use App\Entity\EntityParent;
use Doctrine\Persistence\ManagerRegistry;
use Mazarini\ToolBundle\Repository\EntityRepositoryAbstract;

/**
 * @extends EntityRepositoryAbstract<EntityParent>
 *
 * @method EntityParent|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityParent|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityParent[]    findAll()
 * @method EntityParent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityParentRepository extends EntityRepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityParent::class);
    }

    /**
     * @return EntityParent
     */
    public function getNew($id = 0)
    {
        return new EntityParent();
    }

    /**
     * Undocumented function.
     *
     * @return ?EntityParent
     */
    public function get(int $id)
    {
        return $this->find($id);
    }
}
