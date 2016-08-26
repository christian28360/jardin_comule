<?php

/*
 * Property of LA POSTE
 */

namespace DTC\Modules\GestionReservationAuto\Entity\Organization;

use Doctrine\ORM\Mapping as ORM;
use DTC\Common\Entity\MappedSuperclass\Organization\MappedSuperclassSourceOrga;
use DTC\Common\Interfaces\Organization\ISourceOrga;

/**
 * @ORM\Entity(readOnly=true, repositoryClass="\DTC\Common\Repository\Superclass\Organization\RepositorySuperclassSourceOrga")
 * @ORM\Table(name="V_SOURCE_ORGA")
 * @author wug870
 */
class SourceOrga extends MappedSuperclassSourceOrga implements ISourceOrga
{
    /**
     * @ORM\ManyToMany(targetEntity="\DTC\Modules\GestionReservationAuto\Entity\Perimetre", mappedBy="services")
     **/
    private $perimetres;

    
    public function __construct()
    {
        $this->perimetres = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function getPerimetres()
    {
        return $this->perimetres;
    }

    public function setPerimetres($perimetres)
    {
        $this->perimetres = $perimetres;
    }


}
