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

use App\Entity\EntityChild;
use App\Form\EntityChildType;
use App\Repository\EntityChildRepository;
use Mazarini\ToolBundle\Controller\EntityControllerAbstract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/child')]
class EntityChildController extends EntityControllerAbstract
{
    #[Route('/', name: 'app_entity_child_index', methods: ['GET'])]
    public function index(EntityChildRepository $entityRepository): Response
    {
        return $this->render('entity_child/index.html.twig', [
            'entity_children' => $entityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_entity_child_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityChildRepository $entityManager): Response
    {
        $entityChild = new EntityChild();
        $form = $this->createForm(EntityChildType::class, $entityChild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->store($entityChild);

            return $this->redirectToRoute('app_entity_child_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entity_child/new.html.twig', [
            'entity_child' => $entityChild,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entity_child_show', methods: ['GET'])]
    public function show(EntityChild $entityChild): Response
    {
        return $this->render('entity_child/show.html.twig', [
            'entity_child' => $entityChild,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entity_child_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityChild $entityChild, EntityChildRepository $entityManager): Response
    {
        $form = $this->createForm(EntityChildType::class, $entityChild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->store($entityChild);

            return $this->redirectToRoute('app_entity_child_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entity_child/edit.html.twig', [
            'entity_child' => $entityChild,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entity_child_delete', methods: ['POST'])]
    public function delete(Request $request, EntityChild $entityChild, EntityChildRepository $entityManager): Response
    {
        if (!$this->isDeleteTokenValid($request, $entityChild)) {
            return $this->redirectToRoute('app_entity_show', ['id', $entityChild->getId()], Response::HTTP_SEE_OTHER);
        }

        $entityManager->remove($entityChild);

        return $this->redirectToRoute('app_entity_index', [], Response::HTTP_SEE_OTHER);
    }
}
