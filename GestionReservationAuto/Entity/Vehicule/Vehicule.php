<?php

/*
 * Property of LA POSTE
 */

namespace DTC\Modules\GestionReservationAuto\Entity\Vehicule;

use Doctrine\ORM\Mapping as ORM;
use DTC\Common\Entity\MappedSuperclass\Vehicule\MappedSuperclassVehicule;
use DTC\Common\Interfaces\Vehicule\IVehicule;

/**
 * @ORM\Entity(readOnly=true, repositoryClass="\DTC\Common\Repository\Superclass\Vehicule\RepositorySuperclassVehicule")
 * @ORM\Table(name="V_VEHICULE")
 * @author wug870
 */
class Vehicule extends MappedSuperclassVehicule implements IVehicule
{
    /**
     * @ORM\OneToOne(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\Voiture", mappedBy="vehicule")
     * @var \DTC\Modules\GestionReservationAuto\Entity\Voiture
     **/
    private $voiture;

    public function __toString()
    {
        return $this->vehImmat.' - '.$this->mrqLibelle.' - '.$this->mdlLibelle.' - '.$this->locSourceOrga->getSocptRegate();
    }
    
    
    public function getVoiture()
    {
        return $this->voiture;
    }

    public function setVoiture($voiture)
    {
        $this->voiture = $voiture;
    }


    
}
