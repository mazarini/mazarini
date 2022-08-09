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

#[Route('/parent')]
class EntityParentController extends EntityControllerAbstract
{
    #[Route('/', name: 'app_entity_parent_index', methods: ['GET'])]
    public function index(EntityParentRepository $parentRepository): Response
    {
        return $this->render('entity_parent/index.html.twig', [
            'entity_parents' => $parentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_entity_parent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityParentRepository $entityManager): Response
    {
        $entityParent = new EntityParent();
        $form = $this->createForm(EntityParentType::class, $entityParent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->store($entityParent);

            return $this->redirectToRoute('app_entity_parent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entity_parent/new.html.twig', [
            'entity_parent' => $entityParent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entity_parent_show', methods: ['GET'])]
    public function show(EntityParent $entityParent): Response
    {
        return $this->render('entity_parent/show.html.twig', [
            'entity_parent' => $entityParent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entity_parent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityParent $entityParent, EntityParentRepository $entityManager): Response
    {
        $form = $this->createForm(EntityParentType::class, $entityParent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_entity_parent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entity_parent/edit.html.twig', [
            'entity_parent' => $entityParent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entity_parent_delete', methods: ['POST'])]
    public function delete(Request $request, EntityParent $entityParent, EntityParentRepository $entityManager): Response
    {
        if (!$this->isDeleteTokenValid($request, $entityParent)) {
            return $this->redirectToRoute('app_entity_show', ['id', $entityParent->getId()], Response::HTTP_SEE_OTHER);
        }

        $entityManager->remove($entityParent);

        return $this->redirectToRoute('app_entity_index', [], Response::HTTP_SEE_OTHER);
    }
}
