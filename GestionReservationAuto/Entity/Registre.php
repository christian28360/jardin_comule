<?php

namespace DTC\Modules\GestionReservationAuto\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 * @ORM\Entity(repositoryClass="DTC\Modules\GestionReservationAuto\Repository\RegistreRepository")
 * @ORM\Table(name="T_REGISTRE_REG")
 */
class Registre
{
    /**
     * @var integer
     *
     * @ORM\Column(name="REG_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idRegistre;
    
    /**
     * @ORM\ManyToOne(targetEntity="DTC\Modules\GestionReservationAuto\Entity\User")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="REG_USER", referencedColumnName="USE_ID")
     * })
     */
    private $user;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="REG_DATE_DEBUT", type="datetime")
     */
    private $dateDebut;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="REG_DATE_FIN", type="datetime")
     */
    private $dateFin;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="REG_DATE_CREATION", type="datetime")
     */
    private $dateCreation;
    /**
     * @var string
     * 
     * @ORM\Column(name="REG_SIGNATURE_AGENT", type="string")
     */
    private $signatureAgent;
    /**
     * @var string
     * 
     * @ORM\Column(name="REG_SIGNATURE_SUPERIEUR", type="string")
     */
    private $signatureSuperieur;
    
    /**
     * Get idReservation
     *
     * @return integer 
     */
    public function getIdRegistre()
    {
        return $this->idRegistre;
    }
    /**
     * Set User
     *
     * @param string $user
     * @return Reservation
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User
     *
     * @return User 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Set dateDebut
     *
     * @param string $dateDebut
     * @return Reservation
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return datetime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }
    
    /**
     * Set dateFin
     *
     * @param string $dateFin
     * @return Reservation
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return dateFin 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
    
    /**
     * Set dateCreation
     *
     * @param string $dateCreation
     * @return Reservation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return dateCreation 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }
    /**
     * Set destination
     *
     * @param string $destination
     * @return Reservation
     */
    public function setDistination($destination)
    {
        $this->destination = $destination;

        return $this;
    }
    
    /**
     * Set signatureAgent
     *
     * @param string $signatureAgent
     * @return Reservation
     */
    public function setSignatureAgent($signatureAgent)
    {
        $this->signatureAgent = $signatureAgent;

        return $this;
    }

    /**
     * Get signatureAgent
     *
     * @return signatureAgent 
     */
    public function getSignatureAgent()
    {
        return $this->signatureAgent;
    }
    
    /**
     * Set signatureSuperieur
     *
     * @param string $signatureSuperieur
     * @return Reservation
     */
    public function setSignatureSuperieur($signatureSuperieur)
    {
        $this->signatureSuperieur = $signatureSuperieur;

        return $this;
    }

    /**
     * Get signatureSuperieur
     *
     * @return signatureSuperieur 
     */
    public function getSignatureSuperieur()
    {
        return $this->signatureSuperieur;
    }
}
