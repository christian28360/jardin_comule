<?php

/*
 * Property of LA POSTE
 */

namespace DTC\Modules\GestionReservationAuto\Entity\GestionRh;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DTC\Common\Entity\MappedSuperclass\GestionRh\MappedSuperclassQuotite;
use DTC\Common\Interfaces\GestionRh\IQuotite;

/**
 * @ORM\Entity
 * @ORM\Table(name="V_QUOTITE_QUO")
 * @author glr735
 */
class Quotite extends MappedSuperclassQuotite implements IQuotite
{

    /**
     * @{inherit}
     */
    protected $entityManagerName = 'gestionRh';

    /**
     * @{inherit}
     */
    protected $entityName = __CLASS__;

}
