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

namespace App\Entity;

use App\Repository\EntityParentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mazarini\ToolBundle\Entity\Entity;

#[ORM\Entity(repositoryClass: EntityParentRepository::class)]
class EntityParent extends Entity
{
    #[ORM\Column(length: 31)]
    private string $slug = '';

    /**
     * @var Collection<int, EntityChild>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: EntityChild::class)]
    private Collection $childs;

    public function __construct()
    {
        $this->childs = new ArrayCollection();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, EntityChild>
     */
    public function getChilds(): Collection
    {
        return $this->childs;
    }

    public function addChild(EntityChild $child): self
    {
        if (!$this->childs->contains($child)) {
            $this->childs->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(EntityChild $child): self
    {
        if ($this->childs->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%d)', $this->getSlug(), $this->getId());
    }
}
