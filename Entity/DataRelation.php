<?php

namespace DavidBadura\FixturesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ext_data_relation")
 */
class DataRelation
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue 
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $dataKey;

    /**
     * @ORM\Column(type="string", length=64) 
     */
    protected $dataType;

    /**
     * @ORM\Column(type="integer") 
     */
    protected $entityId;

    /**
     * @ORM\Column(type="string", length=255) 
     */
    protected $entityClass;

    /**
     * @ORM\Column(type="datetime") 
     */
    protected $createDate;

    public function __construct($dataType, $dataKey, $entityClass, $entityId)
    {

        $this->createDate = new \DateTime('NOW');
        $this->dataKey = $dataKey;
        $this->dataType = $dataType;
        $this->entityId = $entityId;
        $this->entityClass = $entityClass;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDataKey()
    {
        return $this->dataKey;
    }

    public function setDataKey($dataKey)
    {
        $this->dataKey = $dataKey;
        return $this;
    }

    public function getDataType()
    {
        return $this->dataType;
    }

    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
    }

    public function getEntityId()
    {
        return $this->entityId;
    }

    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

}