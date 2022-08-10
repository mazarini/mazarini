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

namespace App\Test\Controller;

use App\Entity\Entity;
use App\Repository\EntityRepository;
use Mazarini\ToolBundle\Test\Controller\EntityControllerTestAbstract;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use TypeError;

/**
 * @extends EntityControllerTestAbstract<Entity,EntityRepository>
 */
class EntityControllerTest extends EntityControllerTestAbstract
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->routeRoot = 'app_entity';
        foreach (['slug', '$slug1', '$slug2', '$slug3'] as $slug) {
            $this->getRepository()->store($this->getRepository()->getNew()->setSlug($slug));
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->getPath('_index'));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Entity index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjects = \count($this->getRepository()->findAll());

        $this->client->request('GET', $this->getPath('_new'));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'entity[slug]' => 'Testing',
        ]);

        $fixtures = $this->getRepository()->findby([], ['id' => 'desc']);
        self::assertSame($originalNumObjects + 1, \count($fixtures));

        self::assertResponseRedirects($this->getPath('_show', $fixtures[0]));
    }

    public function testShow(): void
    {
        $fixture = $this->getRepository()->getNew();
        $fixture->setSlug('My Title');

        $this->getRepository()->store($fixture);

        $this->client->request('GET', $this->getPath('_show', $fixture));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Entity');
    }

    public function testEdit(): void
    {
        $fixture = new Entity();
        $fixture->setSlug('My Title');
        $this->getRepository()->store($fixture);
        $id = $fixture->getId();

        $this->client->request('GET', $this->getPath('_edit', $fixture));

        $this->client->submitForm('Update', [
            'entity[slug]' => 'Something New',
        ]);

        self::assertResponseRedirects($this->getPath('_show', $fixture));

        $fixture = $this->getRepository()->get($id);

        self::assertNotNull($fixture);
        if (null !== $fixture) {
            self::assertSame('Something New', $fixture->getSlug());
        }
    }

    public function testRemove(): void
    {
        $originalNumObjects = \count($this->getRepository()->findAll());

        $fixture = new Entity();
        $fixture->setSlug('My Title');

        $this->getRepository()->store($fixture);

        self::assertSame($originalNumObjects + 1, \count($this->getRepository()->findAll()));

        $this->client->request('GET', $this->getPath('_show', $fixture));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjects, \count($this->getRepository()->findAll()));
        self::assertResponseRedirects($this->getPath('_index'));
    }

    /**
     * @return Entity
     */
    protected function getNewEntity()
    {
        return new Entity();
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        $repository = $this->getRegistry()->getRepository(Entity::class);
        if (is_a($repository, EntityRepository::class)) {
            return $repository;
        }
        throw new TypeError();
    }
}
