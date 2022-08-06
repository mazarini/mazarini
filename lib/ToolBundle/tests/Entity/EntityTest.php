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

namespace AppToolBundle\Tests\Entity;

use App\Entity\Entity;
use Mazarini\ToolBundle\Test\Entity\EntityTestAbstract;

/**
 * @extends EntityTestAbstract<Entity>
 */
class EntityTest extends EntityTestAbstract
{
    /**
     * @return Entity
     */
    protected function getNewEntity()
    {
        return new Entity();
    }

    public function getGetterSetterData(): array
    {
        return [
            ['slug', '', 'string'],
        ];
    }
}
