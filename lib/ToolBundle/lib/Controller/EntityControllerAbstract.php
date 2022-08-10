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
use Mazarini\ToolBundle\Repository\EntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TypeError;

/**
 * Undocumented class.
 *
 * @template E of EntityInterface
 * @template R of EntityRepositoryInterface
 */
class EntityControllerAbstract extends AbstractController
{
    protected string $baseName = '';
    protected string $routeFormat = 'app_%s_%s';
    protected string $templateFormat = '%s/%s.html.twig';

    /**
     * Undocumented function.
     *
     * @param R $entityRepository
     * @param E $entity
     */
    protected function editAction(Request $request, $entityRepository, $entity, string $class): Response
    {
        $form = $this->createForm($class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isEntityValid($entityRepository, $entity)) {
            $entityRepository->store($entity);

            return $this->redirectToRoute($this->getRouteName('show'), ['id' => $entity->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm($this->getTemplate($entity->isNew() ? 'new' : 'edit'), [
            'entity' => $entity,
            'form' => $form,
        ]);
    }

    /**
     * Undocumented function.
     *
     * @param ?R $entityRepository
     * @param ?E $entity
     */
    protected function isEntityValid($entityRepository, $entity): bool
    {
        if (null === $entityRepository) {
            throw new TypeError();
        }
        if (null === $entity) {
            throw new TypeError();
        }

        return true;
    }

    protected function getRouteName(string $function): string
    {
        $method = __METHOD__.ucfirst($function);
        if (method_exists($this, $method)) {
            return $this->$method($function);
        }

        return sprintf($this->routeFormat, $this->baseName, $function);
    }

    protected function getTemplate(string $function): string
    {
        $method = __METHOD__.ucfirst($function);
        if (method_exists($this, $method)) {
            return $this->$method($function);
        }

        return sprintf($this->templateFormat, $this->baseName, $function);
    }

    /**
     * Undocumented function.
     *
     * @param E $entity
     * @param R $entityRepository
     */
    protected function deleteAction(Request $request, $entity, $entityRepository): Response
    {
        if (!$this->isDeleteTokenValid($request, $entity)) {
            return $this->redirectToRoute($this->getRouteName('show'), ['id', $entity->getId()], Response::HTTP_SEE_OTHER);
        }

        $entityRepository->remove($entity);

        return $this->redirectToRoute($this->getRouteName('index'), [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Undocumented function.
     *
     * @param ?R $entityRepository
     * @param ?E $entity
     */
    protected function isEntityRemovable($entityRepository, $entity): bool
    {
        if (null === $entityRepository) {
            throw new TypeError();
        }
        if (null === $entity) {
            throw new TypeError();
        }

        return true;
    }

    /**
     * Undocumented function.
     *
     * @param E $entity
     */
    protected function isDeleteTokenValid(Request $request, $entity): bool
    {
        $token = $request->request->get('_token');
        if (\is_string($token)) {
            return $this->isCsrfTokenValid('delete'.$entity->getId(), $token);
        }

        return false;
    }
}
