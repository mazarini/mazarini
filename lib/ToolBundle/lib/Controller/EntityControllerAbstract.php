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

namespace Mazarini\ToolBundle\Controller;

use Mazarini\ToolBundle\Entity\EntityInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Undocumented class.
 */
class EntityControllerAbstract extends AbstractController
{
    protected function isDeleteTokenValid(Request $request, EntityInterface $entity): bool
    {
        $token = $request->request->get('_token');
        if (\is_string($token)) {
            return $this->isCsrfTokenValid('delete'.$entity->getId(), $token);
        }

        return false;
    }
}
