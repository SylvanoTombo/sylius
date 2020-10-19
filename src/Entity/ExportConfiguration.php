<?php

namespace App\Entity;

use App\Repository\ExportConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity(repositoryClass=ExportConfigurationRepository::class)
 */
class ExportConfiguration implements ResourceInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $entity;

    /**
     * @ORM\Column(type="json")
     */
    private $enableFields;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getEnableFields()
    {
        return $this->enableFields;
    }

    /**
     * @param mixed $entity
     * @return ExportConfiguration
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param mixed $enableFields
     * @return ExportConfiguration
     */
    public function setEnableFields($enableFields)
    {
        $this->enableFields = $enableFields;
        return $this;
    }
}
