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
use App\Entity\EntityParent;
use App\Form\EntityChildType;
use App\Repository\EntityChildRepository;
use Mazarini\ToolBundle\Controller\EntityControllerAbstract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @template E EntityChild
 * @template R EntityChildRepository
 *
 * @template-extends EntityControllerAbstract<EntityChild,EntityChildRepository>
 */
#[Route('/child')]
class EntityChildController extends EntityControllerAbstract
{
    #[Route('/{id}/new.html', name: 'app_entity_child_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityChildRepository $entityManager, EntityParent $entityParent): Response
    {
        $entityChild = new EntityChild();
        $entityChild->setParent($entityParent);
        $id = $entityParent->getId();
        $form = $this->createForm(EntityChildType::class, $entityChild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->store($entityChild);

            return $this->redirectToRoute('app_entity_parent_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entity_child/new.html.twig', [
            'entity' => $entityChild,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show.html', name: 'app_entity_child_show', methods: ['GET'])]
    public function show(EntityChild $entityChild): Response
    {
        return $this->render('entity_child/show.html.twig', [
            'entity' => $entityChild,
        ]);
    }

    #[Route('/{id}/edit.html', name: 'app_entity_child_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityChild $entityChild, EntityChildRepository $entityManager): Response
    {
        $id = 0;
        $parent = $entityChild->getParent();
        if ($parent !== null) {
            $id = $parent->getId();
        }
        $form = $this->createForm(EntityChildType::class, $entityChild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->store($entityChild);

            return $this->redirectToRoute('app_entity_parent_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entity_child/edit.html.twig', [
            'entity' => $entityChild,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete.html', name: 'app_entity_child_delete', methods: ['POST'])]
    public function delete(Request $request, EntityChild $entityChild, EntityChildRepository $entityManager): Response
    {
        $parentId = $entityChild->getParent()->getId();
        if (!$this->isDeleteTokenValid($request, $entityChild)) {
            return $this->redirectToRoute('app_entity_show', ['id', $entityChild->getId()], Response::HTTP_SEE_OTHER);
        }

        $entityManager->remove($entityChild);

        return $this->redirectToRoute('app_entity_parent_show', ['id' => $parentId], Response::HTTP_SEE_OTHER);
    }
}
