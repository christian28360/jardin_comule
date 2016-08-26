<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DTC\Modules\GestionReservationAuto\Entity\GestionRh;

use Doctrine\ORM\Mapping as ORM;
use DTC\Common\Entity\MappedSuperclass\GestionRh\MappedSuperclassAgent;
use DTC\Common\Interfaces\GestionRh\IAgentRh;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(readOnly=true, repositoryClass="DTC\Common\Repository\Superclass\GestionRh\RepositorySuperclassAgent")
 * @ORM\Table(name="V_AGENT_DT_AGEN")
 * @author glr735
 */
class Agent extends MappedSuperclassAgent implements IAgentRh
{
    
}
