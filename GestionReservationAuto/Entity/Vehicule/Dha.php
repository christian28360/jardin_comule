<?php

/*
 * Property of LA POSTE
 */

namespace DTC\Modules\GestionReservationAuto\Entity\Vehicule;

use Doctrine\ORM\Mapping as ORM;
use DTC\Common\Entity\MappedSuperclass\Vehicule\MappedSuperclassDha;
use DTC\Common\Interfaces\Vehicule\IDha;

/**
 * @ORM\Entity(readOnly=true, repositoryClass="\DTC\Common\Repository\Superclass\Vehicule\RepositorySuperclassVehicule")
 * @ORM\Table(name="V_DHA")
 * @author wug870
 */
class Dha extends MappedSuperclassDha implements IDha
{
    

}
