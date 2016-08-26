<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DTC\Modules\GestionReservationAuto\Entity\GestionRh;

use Doctrine\ORM\Mapping as ORM;
use DTC\Common\Entity\MappedSuperclass\GestionRh\MappedSuperclassAssignment;
use DTC\Common\Interfaces\GestionRh\IAssignmentRh;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="V_ASSIGNMENT_ASGT")
 * @author glr735
 */
class Assignment extends MappedSuperclassAssignment implements IAssignmentRh
{
    
}
