<?php

namespace DTC\Modules\GestionReservationAuto\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Reservation
 * @ORM\Entity(repositoryClass="DTC\Modules\GestionReservationAuto\Repository\ReservationRepository")
 * @ORM\Table(name="T_RESERVATION_RES")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Reservation
{

    /**
     * @var \Silex\Application
     */
    public function __construct(User $user = null)
    {
        $this->signatureSuperieur = $user->getHierarchiqueMail();
        $this->dateDebut = new \DateTime("now");
        $this->dateDebut->setTime(7, 30);
        $this->dateFin = new \DateTime("now");
        $this->dateFin->setTime(17, 30);
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="RES_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idReservation;

    /**
     * @ORM\ManyToOne(targetEntity="DTC\Modules\GestionReservationAuto\Entity\User", inversedBy="reservations")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="RES_USER", referencedColumnName="USE_ID")
     * })
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="DTC\Modules\GestionReservationAuto\Entity\Voiture", inversedBy="reservations")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="RES_VOITURE", referencedColumnName="VOI_ID")
     * })
     */
    private $voiture;

    /**
     * RM\ManyToOne(targetEntity="DTC\Modules\GestionReservationAuto\Entity\Voiture", inversedBy="reservations")
     * RM\JoinColumns({
     *  RM\JoinColumn(name="RES_VOITURE", referencedColumnName="VOI_ID")
     * })
     */
    private $nomSuperieur;

    function getMailSuperieur()
    {
        return $this->nomSuperieur;
    }

    function setNomSuperieur($nomSuperieur)
    {
        $this->nomSuperieur = $nomSuperieur;
    }

    /**
     * @var DateTime
     * 
     * @ORM\Column(name="RES_DATE_DEBUT", type="datetime")
     */
    private $dateDebut;

    /**
     * @var DateTime
     * 
     * @ORM\Column(name="RES_DATE_FIN", type="datetime")
     */
    private $dateFin;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="RES_DATE_CREATION", type="datetime")
     */
    private $dateCreation;

    /**
     * @var string
     * 
     * @ORM\Column(name="RES_DESTINATION", type="string")
     */
    private $destination;

    /**
     * @var string
     * 
     * @ORM\Column(name="RES_DEPART", type="string")
     */
    private $depart;

    /**
     * @var int
     * 
     * @ORM\Column(name="RES_DISTANCE", type="integer")
     */
    private $distance;

    /**
     * @var string
     * 
     * @ORM\Column(name="RES_MOTIF", type="string")
     */
    private $motif;

    /**
     * @var string
     * 
     * @ORM\Column(name="RES_SIGNATURE_AGENT", type="string")
     */
    private $signatureAgent;

    /**
     * @var string
     * Le code RH du N+1
     * @ORM\Column(name="RES_SIGNATURE_HIERARCHIQUE", type="string")
     */

    /**
     * RM\ManyToOne(targetEntity="DTC\Modules\GestionReservationAuto\Entity\User", inversedBy="reservations")
     * RM\JoinColumns({
     *  RM\JoinColumn(name="RES_USER", referencedColumnName="USE_ID")
     * })
     */
    private $signatureHierarchique;

    /**
     * @var string
     * Le code RH du N+1
     * @ORM\Column(name="RES_SIGNATURE_SUPERIEUR", type="string")
     */
    private $signatureSuperieur;

    /**
     * @var string
     * Le code RH du validateur
     * 
     * @ORM\Column(name="RES_SIGNATURE_VALIDATEUR", type="string")
     */
    private $signatureValidateur;

    /**
     * @var string
     * 
     * @ORM\Column(name="RES_REMISAGE_DOMICILE", type="boolean")
     */
    private $remisage;

    /**
     * @var string
     * 
     * @ORM\Column(name="RES_ACCOMPAGNANT_1", type="string")
     */
    private $accompagnant1;

    /**
     * @var string
     * 
     * @ORM\Column(name="RES_ACCOMPAGNANT_2", type="string")
     */
    private $accompagnant2;

    /**
     * @var string
     * 
     * @ORM\Column(name="RES_ACCOMPAGNANT_3", type="string")
     */
    private $accompagnant3;

    /**
     * @var string
     * 
     * @ORM\Column(name="RES_ACCOMPAGNANT_4", type="string")
     */
    private $accompagnant4;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="RES_CREATE_BY", type="string")
     */
    private $createBy;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="RES_UPDATE_AT", type="datetime")
     */
    private $updateAt;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="RES_UPDATE_BY", type="string")
     */
    private $updateBy;

    /**
     * @ORM\Column(name="RES_DELETE_AT", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Retourne la liste des minutes autorisées dans les formulaires
     * de réservation de véhicule.
     * @return array
     */
    public static function getMinutesAutorisees()
    {
        return array(0, 15, 30, 45);
    }

    /**
     * Validator of object, called by Validator implementation.
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraint('voiture', new Assert\NotNull());

        // La signature du validateur est-elle correcte, si renseignée ?
        $metadata->addConstraint(new Assert\Callback(function ($object, ExecutionContextInterface $context) {
            if ($object->getSignatureValidateur()) {
                \DTC\Common\Validator\MailValidator::validateMail(
                        'gestionReservationAuto', 'DTC\Modules\GestionReservationAuto\Entity\GestionRh\SourceAgent', $object->getSignatureValidateur(), $context
                );
                /*
                  $rep = $app['orm.ems']['gestionReservationAuto']
                  ->getRepository("DTC\Modules\GestionReservationAuto\Entity\User")
                  ->findByMail();

                  //                $validateur = \DTC\Modules\GestionReservationAuto\Entity\User::findByMail();

                  var_dump($rep);
                 */
            }
//            exit();
        }));

        $metadata->addPropertyConstraint('dateDebut', new Assert\NotBlank());
        $metadata->addPropertyConstraint('dateDebut', new Assert\DateTime());

        $metadata->addPropertyConstraint('dateFin', new Assert\NotBlank());
        $metadata->addPropertyConstraint('dateFin', new Assert\DateTime());

        $metadata->addConstraint(new Assert\Callback(function ($object, ExecutionContextInterface $context) {

            $duree = $object->getDateDebut()->diff($object->getDateFin())->format('%d') * 24;
            $duree += $object->getDateDebut()->diff($object->getDateFin())->format('%h');

            if ($duree < ($object->getDistance() / 100)) {
                $context->addViolation(
                        'La durée de réservation en heures (' . $duree . ') ne peut être inférieure au rapport de la distance A/R par 100'
                );
            }
        }));

        $metadata->addConstraint(new Assert\Callback(function ($object, ExecutionContextInterface $context) {

            if ($object->getDateFin() < $object->getDateDebut()) {
                $context->addViolation(
                        'La date et heure de fin doivent être strictement supérieures à la celles de début'
                );
            }
        }));

        $metadata->addPropertyConstraint('accompagnant1', new Assert\Length(array('min' => 0, 'max' => 50)));
        $metadata->addPropertyConstraint('accompagnant2', new Assert\Length(array('min' => 0, 'max' => 50)));
        $metadata->addPropertyConstraint('accompagnant3', new Assert\Length(array('min' => 0, 'max' => 50)));
        $metadata->addPropertyConstraint('accompagnant4', new Assert\Length(array('min' => 0, 'max' => 50)));
    }

    function getRemisage()
    {
        return $this->remisage;
    }

    function getAccompagnant1()
    {
        return $this->accompagnant1;
    }

    function getNomSuperieur()
    {
        return $this->nomSuperieur;
    }

    function getAccompagnant2()
    {
        return $this->accompagnant2;
    }

    function getAccompagnant3()
    {
        return $this->accompagnant3;
    }

    function getAccompagnant4()
    {
        return $this->accompagnant4;
    }

    function getSignatureValidateur()
    {
        return $this->signatureValidateur;
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

    public function getIdReservation()
    {
        return $this->idReservation;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getVoiture()
    {
        return $this->voiture;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getDepart()
    {
        return $this->depart;
    }

    public function getDistance()
    {
        return $this->distance;
    }

    public function getMotif()
    {
        return $this->motif;
    }

    public function getSignatureAgent()
    {
        return $this->signatureAgent;
    }

    function getSignatureHierarchique()
    {
        return $this->signatureHierarchique;
    }

    public function getSignatureSuperieur()
    {
        return $this->signatureSuperieur;
    }

    public function setIdReservation($idReservation)
    {
        $this->idReservation = $idReservation;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setVoiture($voiture)
    {
        $this->voiture = $voiture;
    }

    public function setDateDebut($dateDebut = null)
    {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin($dateFin = null)
    {
        $this->dateFin = $dateFin;
    }

    public function setDateCreation(DateTime $dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    public function setDepart($depart)
    {
        $this->depart = $depart;
    }

    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    public function setMotif($motif)
    {
        $this->motif = $motif;
    }

    public function setSignatureAgent($signatureAgent)
    {
        $this->signatureAgent = $signatureAgent;
    }

    function setSignatureHierarchique($signatureHierarchique)
    {
        $this->signatureHierarchique = $signatureHierarchique;
    }

    public function setSignatureSuperieur($signatureSuperieur)
    {
        $this->signatureSuperieur = $signatureSuperieur;
    }

    function setSignatureValidateur($signatureValidateur)
    {
        $this->signatureValidateur = $signatureValidateur;
    }

    function setRemisage($remisage)
    {
        $this->remisage = $remisage;
    }

    function setAccompagnant1($accompagnant1)
    {
        $this->accompagnant1 = $accompagnant1;
    }

    function setAccompagnant2($accompagnant2)
    {
        $this->accompagnant2 = $accompagnant2;
    }

    function setAccompagnant3($accompagnant3)
    {
        $this->accompagnant3 = $accompagnant3;
    }

    function setAccompagnant4($accompagnant4)
    {
        $this->accompagnant4 = $accompagnant4;
    }

}
