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

namespace Mazarini\ToolBundle\Test\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Mazarini\ToolBundle\Entity\EntityInterface;
use Mazarini\ToolBundle\Repository\EntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TypeError;

/**
 * Undocumented class.
 *
 * @template E of EntityInterface
 * @template R of EntityRepositoryInterface
 */
abstract class EntityControllerTestAbstract extends WebTestCase
{
    protected int $id = 0;
    protected string $routeRoot = '';

    /**
     * @return R
     */
    abstract protected function getRepository();

    protected function setUp(): void
    {
        $entity = $this->getRepository()->getNew(0);
        $this->getRepository()->store($entity);
        $this->id = $entity->getId();
    }

    protected function getRegistry(): Registry
    {
        $registry = static::getContainer()->get('doctrine');
        if (null !== $registry && is_a($registry, Registry::class)) {
            return $registry;
        }
        throw new TypeError();
    }

    protected function getRouter(): Router
    {
        $router = static::getContainer()->get('router');
        if (null !== $router && is_a($router, Router::class)) {
            return $router;
        }
        throw new TypeError();
    }

    /**
     * Undocumented function.
     *
     * @param EntityInterface $entity
     *
     * @return array<string,string|int>
     */
    protected function getRouteParameter($entity): array
    {
        switch (true) {
            case !$entity->isNew():
                return ['id' => $entity->getId()];
        }

        return [];
    }

    /**
     * Undocumented function.
     *
     * @param EntityInterface $entity
     */
    protected function getPath(string $route, $entity = null): string
    {
        if ('_' === substr($route, 0, 1)) {
            $route = $this->routeRoot.$route;
        }
        if (null === $entity) {
            $entity = $this->getRepository()->getNew(0);
        }

        return $this->getRouter()->generate($route, $this->getRouteParameter($entity));
    }
}
