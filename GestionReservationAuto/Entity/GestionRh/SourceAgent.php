<?php

/*
 * Property of LA POSTE
 */

namespace DTC\Modules\GestionReservationAuto\Entity\GestionRh;

use Doctrine\ORM\Mapping as ORM;
use DTC\Common\Entity\MappedSuperclass\GestionRh\MappedSuperclassSourceAgent;
use DTC\Common\Interfaces\GestionRh\ISourceAgent;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="V_SOURCE_AGENTS")
 * @author glr735
 */
class SourceAgent extends MappedSuperclassSourceAgent implements ISourceAgent
{
    /**
     * @ORM\OneToOne(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\User", mappedBy="sourceAgent", fetch="EXTRA_LAZY")
     **/
    protected $gpaUser;
    
    public function getGpaUser()
    {
        return $this->gpaUser;
    }

    public function setGpaUser($gpaUser)
    {
        $this->gpaUser = $gpaUser;
    }

}
