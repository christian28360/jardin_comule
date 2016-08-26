<?php

namespace DTC\Modules\GestionReservationAuto\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Perimetre
 *
 * @ORM\Table(name="T_PERIMETRE_PER")
 * @ORM\Entity(repositoryClass="DTC\Modules\GestionReservationAuto\Repository\PerimetreRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Perimetre
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PER_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idPerimetre;

    /**
     * @var string
     *
     * @ORM\Column(name="PER_LIBELLE", type="string")
     */
    private $libelle;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="PER_CREATE_AT", type="datetime")
     */
    private $createAt;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="PER_CREATE_BY", type="string")
     */
    private $createBy;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="PER_UPDATE_AT", type="datetime")
     */
    private $updateAt;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="PER_UPDATE_BY", type="string")
     */
    private $updateBy;

    /**
     * @ORM\Column(name="PER_DELETE_AT", type="datetime", nullable=true)
     */
    private $deletedAt;
    
    /**
     * @ORM\ManyToMany(targetEntity="DTC\Modules\GestionReservationAuto\Entity\Organization\SourceOrga", inversedBy="perimetres", indexBy="code")
     * @ORM\JoinTable(name="T_RELATION_PER_ROC",
     *      joinColumns={@ORM\JoinColumn(name="PERSER_PER_ID", referencedColumnName="PER_ID")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="PERSER_ENT_CODE", referencedColumnName="ENT_CODE", unique=true)}
     *      )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $services;
    
    /**
     * @var array
     */
    private $listeServices;
    
    
    public function __construct()
    {
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    
    function getIdPerimetre() {
        return $this->idPerimetre;
    }

    function getLibelle() {
        return $this->libelle;
    }

    function getCreateAt() {
        return $this->createAt;
    }

    function getCreateBy() {
        return $this->createBy;
    }

    function getUpdateAt() {
        return $this->updateAt;
    }

    function getUpdateBy() {
        return $this->updateBy;
    }

    function getDeletedAt() {
        return $this->deletedAt;
    }

    function setIdPerimetre($idPerimetre) {
        $this->idPerimetre = $idPerimetre;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function setCreateAt($createAt) {
        $this->createAt = $createAt;
    }

    function setCreateBy($createBy) {
        $this->createBy = $createBy;
    }

    function setUpdateAt($updateAt) {
        $this->updateAt = $updateAt;
    }

    function setUpdateBy($updateBy) {
        $this->updateBy = $updateBy;
    }

    function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }

    public function getServices() {
        return $this->services;
    }

    public function setServices($services) {
        $this->services = $services;
    }

    public function getListeServices($noInit = false)
    {
        if (empty($this->listeServices) && $noInit === false) 
            return $this->listeServices = $this->services->getKeys();
        
        return $this->listeServices;
    }

    public function setListeServices($listeServices)
    {
        $this->listeServices = $listeServices;
    }

}
