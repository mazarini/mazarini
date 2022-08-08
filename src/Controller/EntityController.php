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
use Mazarini\ToolBundle\Entity\EntityInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entity')]
class EntityController extends AbstractController
{
    #[Route('/', name: 'app_entity_index', methods: ['GET'])]
    public function index(EntityRepository $entityRepository): Response
    {
        return $this->render('entity/index.html.twig', [
            'entities' => $entityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_entity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityRepository $entityRepository): Response
    {
        $entity = $entityRepository->getNew();
        $form = $this->createForm(EntityType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityRepository->store($entity);

            return $this->redirectToRoute('app_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entity/new.html.twig', [
            'entity' => $entity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entity_show', methods: ['GET'])]
    public function show(Entity $entity): Response
    {
        return $this->render('entity/show.html.twig', [
            'entity' => $entity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entity $entity, EntityRepository $entityRepository): Response
    {
        $form = $this->createForm(EntityType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityRepository->store($entity);

            return $this->redirectToRoute('app_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entity/edit.html.twig', [
            'entity' => $entity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entity_delete', methods: ['POST'])]
    public function delete(Request $request, Entity $entity, EntityRepository $entityRepository): Response
    {
        if (!$this->isDeleteTokenValid($request, $entity)) {
            return $this->redirectToRoute('app_entity_show', ['id', $entity->getId()], Response::HTTP_SEE_OTHER);
        }

        $entityRepository->remove($entity);

        return $this->redirectToRoute('app_entity_index', [], Response::HTTP_SEE_OTHER);
    }

    protected function isDeleteTokenValid(Request $request, EntityInterface $entity): bool
    {
        $token = $request->request->get('_token');
        if (\is_string($token)) {
            return $this->isCsrfTokenValid('delete'.$entity->getId(), $token);
        }

        return false;
    }
}
