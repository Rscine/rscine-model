<?php

namespace Rscine\WorkerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Hateoas\Configuration\Relation;
use Hateoas\Configuration\Route;
use Hateoas\Configuration\Metadata\ClassMetadataInterface;

use Rscine\WorkerBundle\Entity\Individual;
use Rscine\WorkerBundle\Entity\Worker;

/**
 * Company
 *
 * @ORM\Table()
 * @ORM\Entity()
 *
 * @Serializer\ExclusionPolicy("ALL")
 *
 * @Hateoas\RelationProvider("addEmployeesRelation")
 */
class Company extends Worker
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="siret", type="integer")
     *
     * @Serializer\Expose
     */
    private $siret;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $name;

    /**
     * @var Worker
     *
     * @ORM\OneToMany(targetEntity="Individual", mappedBy="company")
     */
    private $employees;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set siret
     *
     * @param integer $siret
     *
     * @return Company
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return integer
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add employee
     *
     * @param Individual $employee
     *
     * @return Company
     */
    public function addEmployee(Individual $employee)
    {
        $this->employees[] = $employee;

        return $this;
    }

    /**
     * Remove employee
     *
     * @param Individual $employee
     */
    public function removeEmployee(Individual $employee)
    {
        $this->employees->removeElement($employee);
    }

    /**
     * Get employees
     *
     * @return Collection
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Adds multiple links for employees
     *
     * @param Company                $object
     * @param ClassMetadataInterface $classMetadata
     */
    public function addEmployeesRelation($object, ClassMetadataInterface $classMetadata)
    {
        $relations = [];

        foreach ($this->getEmployees() as $employee) {
            $relations[] = new Relation(
                'employees',
                new Route(
                    'get_individual',
                    array('individual' => $employee->getId())
                ));
        }

        return $relations;
    }
}
