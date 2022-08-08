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

use App\Entity\EntityChild;
use Doctrine\Persistence\ManagerRegistry;
use Mazarini\ToolBundle\Repository\EntityRepositoryAbstract;

/**
 * @extends EntityRepositoryAbstract<EntityChild>
 *
 * @method EntityChild|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityChild|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityChild[]    findAll()
 * @method EntityChild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityChildRepository extends EntityRepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityChild::class);
    }

    /**
     * @return EntityChild
     */
    public function getNew($id = 0)
    {
        return new EntityChild();
    }

    /**
     * Undocumented function.
     *
     * @return ?EntityChild
     */
    public function get(int $id)
    {
        return $this->find($id);
    }
}
