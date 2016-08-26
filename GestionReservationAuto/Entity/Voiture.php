<?php

namespace DTC\Modules\GestionReservationAuto\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="DTC\Modules\GestionReservationAuto\Repository\VoitureRepository")
 * @ORM\Table(name="T_VOITURE_VOI")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Voiture extends \DTC\Common\Entity\Master\AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="VOI_ID", type="integer")
     * @ORM\GeneratedValue("AUTO")
     */
    private $idVoiture;

    /**
     * @var string
     *
     * @ORM\Column(name="VOI_COMMENTAIRE", type="string")
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="VOI_STATUT", type="string")
     */
    private $statut;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="VOI_CREATE_AT", type="datetime")
     */
    private $createAt;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="VOI_CREATE_BY", type="string")
     */
    private $createBy;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="VOI_UPDATE_AT", type="datetime")
     */
    private $updateAt;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="VOI_UPDATE_BY", type="string")
     */
    private $updateBy;

    /**
     * @ORM\Column(name="VOI_DELETE_AT", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToOne(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\Vehicule\Vehicule", inversedBy="voiture")
     * @ORM\JoinColumn(name="VOI_VEH_CODE_PARC", referencedColumnName="VEH_CODE_PARC")
     * @var \DTC\Modules\GestionReservationAuto\Entity\Vehicule\Vehicule
     * */
    private $vehicule;

    /**
     * @ORM\OneToMany(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\Reservation", mappedBy="voiture", cascade={"remove"})
     * */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->statut == "attribuer") {
            $statut = $this->statut;
        } else {
            $statut = null;
        }
        return $this->vehicule->getVehImmat() . ' ' . $this->vehicule->getMdlLibelle() . ' ' . $this->commentaire . ' ' . $statut;
    }

    public function getIdVoiture()
    {
        return $this->idVoiture;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function getCreateAt()
    {
        return $this->createAt;
    }

    public function getCreateBy()
    {
        return $this->createBy;
    }

    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    public function getUpdateBy()
    {
        return $this->updateBy;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function getVehicule()
    {
        return $this->vehicule;
    }

    public function setIdVoiture($idVoiture)
    {
        $this->idVoiture = $idVoiture;
    }

    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;
    }

    public function setCreateBy($createBy)
    {
        $this->createBy = $createBy;
    }

    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
    }

    public function setUpdateBy($updateBy)
    {
        $this->updateBy = $updateBy;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    public function setVehicule(\DTC\Modules\GestionReservationAuto\Entity\Vehicule\Vehicule $vehicule)
    {
        $this->vehicule = $vehicule;
    }

    public function getReservations()
    {
        return $this->reservations;
    }

    public function setReservations($reservations)
    {
        $this->reservations = $reservations;
    }

}
