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

namespace App\Controller;

use App\Entity\Entity;
use App\Form\EntityType;
use App\Repository\EntityRepository;
use Mazarini\ToolBundle\Controller\EntityControllerAbstract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @template E Entity
 * @template R EntityRepository

 * @template-extends EntityControllerAbstract<Entity,EntityRepository>
 */
#[Route('/entity')]
class EntityController extends EntityControllerAbstract
{
    protected string $baseName = 'entity';

    #[Route('/', name: 'app_entity_index', methods: ['GET'])]
    public function index(EntityRepository $entityRepository): Response
    {
        return $this->render($this->getTemplate('index'), [
            'entities' => $entityRepository->findAll(),
        ]);
    }

    #[Route('/0/new.html', name: 'app_entity_new', methods: ['GET', 'POST'], requirements: ['$id' => '0'])]
    public function new(Request $request, EntityRepository $entityRepository): Response
    {
        return $this->editAction($request, $entityRepository, $entityRepository->getNew(), EntityType::class);
    }

    #[Route('/{id}/edit.html', name: 'app_entity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityRepository $entityRepository, Entity $entity): Response
    {
        return $this->editAction($request, $entityRepository, $entity, EntityType::class);
    }

    #[Route('/{id}/show.html', name: 'app_entity_show', methods: ['GET'])]
    public function show(Entity $entity): Response
    {
        return $this->render($this->getTemplate('show'), [
            'entity' => $entity,
        ]);
    }

    /**
     * Undocumented function.
     */
    #[Route('/{id}/delete', name: 'app_entity_delete', methods: ['POST'])]
    public function delete(Request $request, Entity $entity, EntityRepository $entityRepository): Response
    {
        return $this->deleteAction($request, $entity, $entityRepository);
    }
}
