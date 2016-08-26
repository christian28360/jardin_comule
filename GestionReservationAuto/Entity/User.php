<?php

namespace DTC\Modules\GestionReservationAuto\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="DTC\Modules\GestionReservationAuto\Repository\UserRepository")
 * @ORM\Table(name="T_USER_USE")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User extends \DTC\Common\Entity\Master\AbstractEntity
{

    public function __construct()
    {
        $this->reservations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->registres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(name="USE_ID", type="string")
     */
    private $idUser;

    /**
     * @ORM\Column(name="USE_ADRESSE", type="string")
     */
    private $adresse;

    /**
     * @ORM\Column(name="USE_CP", type="string")
     */
    private $cp;

    /**
     * @ORM\Column(name="USE_VILLE", type="string")
     */
    private $ville;

    /**
     * @ORM\Column(name="USE_TELEPHONE", type="string")
     */
    private $telephone;

    /**
     * @ORM\Column(name="USE_GRADE", type="string")
     */
    private $grade;

    /**
     * @ORM\OneToOne(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\Perimetre")
     * @ORM\JoinColumn(name="USE_PER_ID", referencedColumnName="PER_ID")
     */
    private $perimetre;

    /**
     * @ORM\Column(name="USE_MAIL_SUPERIEUR", type="string")
     */
    private $mailSuperieur;

    /**
     * @ORM\OneToOne(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\Voiture")
     * @ORM\JoinColumn(name="USE_VOITURE_ATTRIBUE", referencedColumnName="VOI_ID")
     */
    private $voitureAttribue;

    /**
     * @ORM\Column(name="USE_DISTANCE_TRAVAIL_DOMICILE", type="string")
     */
    private $distanceDT;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="USE_CREATE_AT", type="datetime")
     */
    private $createAt;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="USE_CREATE_BY", type="string")
     */
    private $createBy;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="USE_UPDATE_AT", type="datetime")
     */
    private $updateAt;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="USE_UPDATE_BY", type="string")
     */
    private $updateBy;

    /**
     * @ORM\Column(name="USE_DELETE_AT", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToOne(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\GestionRh\SourceAgent", inversedBy="gpaUser", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="USE_AGE_CODE_DT", referencedColumnName="AGE_CODE_DT")
     * @var \DTC\Modules\GestionReservationAuto\Entity\GestionRh\SourceAgent
     * */
    private $sourceAgent;

    function getHierarchique()
    {
        return $this->sourceAgent->getResponsableHierarchique();
    }

    function getHierarchiqueNomComplet()
    {
        return $this->getHierarchique()->getFullName();
    }

    function getHierarchiqueMail()
    {
        return $this->getHierarchique()->getCtcMessagerie();
    }

    /**
     * @ORM\OneToMany(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\Reservation", mappedBy="user", cascade={"remove"})
     * */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\Registre", mappedBy="user", cascade={"remove"})
     * */
    private $registres;

    /**
     * Alias of SourceAgent.agePrenomUsuel
     * @return string
     */
    public function getPrenom()
    {
        return $this->sourceAgent->getAgePrenomUsuel();
    }

    /**
     * Alias of SourceAgent.ageNomUsuel
     * @return string
     */
    public function getNom()
    {
        return $this->sourceAgent->getAgeNomUsuel();
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function getCp()
    {
        return $this->cp;
    }

    public function getVille()
    {
        return $this->ville;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function getGrade()
    {
        return $this->grade;
    }

    public function getMailSuperieur()
    {
        return $this->mailSuperieur;
    }

    public function getPerimetre()
    {
        return $this->perimetre;
    }

    public function getVoitureAttribue()
    {
        return $this->voitureAttribue;
    }

    public function getDistanceDT()
    {
        return $this->distanceDT;
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

    public function getSourceAgent()
    {
        return $this->sourceAgent;
    }

    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    public function setCp($cp)
    {
        $this->cp = $cp;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    public function setGrade($grade)
    {
        $this->grade = $grade;
    }

    public function setMailSuperieur($mailSuperieur)
    {
        $this->mailSuperieur = $mailSuperieur;
    }

    public function setPerimetre($perimetre)
    {
        $this->perimetre = $perimetre;
    }

    public function setVoitureAttribue($voitureAttribue)
    {
        $this->voitureAttribue = $voitureAttribue;
    }

    public function setDistanceDT($distanceDT)
    {
        $this->distanceDT = $distanceDT;
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

    public function setSourceAgent($sourceAgent)
    {
        $this->sourceAgent = $sourceAgent;
        $this->idUser = $this->sourceAgent->getAgeCodeDt();
    }

    public function __toString()
    {
        return $this->sourceAgent->getAgePrenomUsuel() . " " . $this->sourceAgent->getAgeNomUsuel();
    }

    public function getReservations()
    {
        return $this->reservations;
    }

    public function setReservations($reservations)
    {
        $this->reservations = $reservations;
    }

    public function getRegistres()
    {
        return $this->registres;
    }

    public function setRegistres($registres)
    {
        $this->registres = $registres;
    }

}
