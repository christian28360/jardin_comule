<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DTC\Modules\GestionReservationAuto\Entity;

/**
 * Default controller of application
 *
 * @author glr735
 */
class DefaultController
{
    public function homeAction(Request $request, Application $app)
    {
        return $app['twig']->render('src/GestionReservationAuto/Resources/views/index.html.twig');
    }
}
