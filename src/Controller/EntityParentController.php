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

use App\Entity\EntityParent;
use App\Form\EntityParentType;
use App\Repository\EntityParentRepository;
use Mazarini\ToolBundle\Controller\EntityControllerAbstract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @template E of EntityParent
 * @template R of EntityParentRepository

 * @template-extends EntityControllerAbstract<EntityParent,EntityParentRepository>
 */
#[Route('/parent')]
class EntityParentController extends EntityControllerAbstract
{
    protected string $baseName = 'entity_parent';

    #[Route('/', name: 'app_entity_parent_index', methods: ['GET'])]
    public function index(EntityParentRepository $parentRepository): Response
    {
        return $this->render('entity_parent/index.html.twig', [
            'entities' => $parentRepository->findAll(),
        ]);
    }

    #[Route('/0/new.html', name: 'app_entity_parent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityParentRepository $entityManager): Response
    {
        return $this->editAction($request, $entityManager, $entityManager->getNew(), EntityParentType::class);
    }

    #[Route('/{id}/edit', name: 'app_entity_parent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityParent $entityParent, EntityParentRepository $entityManager): Response
    {
        return $this->editAction($request, $entityManager, $entityParent, EntityParentType::class);
    }

    #[Route('/{id}', name: 'app_entity_parent_show', methods: ['GET'])]
    public function show(EntityParent $entityParent): Response
    {
        return $this->render('entity_parent/show.html.twig', [
            'entity' => $entityParent,
        ]);
    }

    /**
     * Undocumented function.
     *
     * @param EntityParent           $entity
     * @param EntityParentRepository $entityRepository
     */
    #[Route('/{id}/delete.html', name: 'app_entity_parent_delete', methods: ['POST'])]
    public function delete(Request $request, EntityParent $entity, EntityParentRepository $entityRepository): Response
    {
        return $this->deleteAction($request, $entity, $entityRepository);
    }
}
