<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DTC\Modules\GestionReservationAuto\Entity\GestionRh;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DTC\Common\Entity\MappedSuperclass\GestionRh\MappedSuperclassService;
use DTC\Common\Interfaces\GestionRh\IServiceRh;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="V_SERVICE_SRV")
 * @author glr735
 */
class Service extends MappedSuperclassService implements IServiceRh
{
    
    /**
     * Name of the service with code (code + name)
     * @return string
     */
    public function getCodeAndName()
    {
        return $this->code . ' - ' . $this->name;
    }
    
}
