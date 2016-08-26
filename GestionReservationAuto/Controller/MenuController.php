<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller of application
 *
 * @author wug870
 */
class MenuController extends GlobalController
{
    
    protected function gradeMenuAction(Request $request, Application $app)
    {
        $user = $this->getGPAUser();
        return $app['twig']->render(
            'src/GestionReservationAuto/Resources/views/fragments/menu.html.twig', 
            array(
                'user' => $user,
                'menu' => $this->request->get('menu'),
            )
        );
    }
    
}
