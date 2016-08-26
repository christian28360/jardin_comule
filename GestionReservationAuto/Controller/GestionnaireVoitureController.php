<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DTC\Modules\GestionReservationAuto\Entity\Voiture;
use DTC\Modules\GestionReservationAuto\Form\VoitureByServiceForm;

/**
 * Default controller of application
 *
 * @author wug870
 */
class GestionnaireVoitureController extends GestionnaireController
{

    protected function homeAction(Request $request, Application $app)
    {
        $vehicule = $this->getRepository('gestionReservationAuto', 'DTC\Modules\GestionReservationAuto\Entity\Vehicule\Vehicule')
                ->findByLocSourceOrga($this->getGPAUser()->getPerimetre()->getListeServices());
        
        $lesVoitures = $this->getRepository('gestionReservationAuto', 'DTC\Modules\GestionReservationAuto\Entity\Voiture')
                ->findByVehicule($vehicule);
        
        return $this->app['twig']->render(
            'src/GestionReservationAuto/Resources/views/voiture/readByService.html.twig', array(
                "lesVoitures" => $lesVoitures,
            )
        );
    }

    /**
     * Create voiture
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function createAction(Request $request, Application $app)
    {
        $voiture = new Voiture();
        $services = array();
        $so = $this->getGPAUser()->getPerimetre()->getServices();
        foreach($so as $s){
            $services[] = $s;
        }
        $vehicules = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Voiture")
                ->findVehicules();
        //Si pas en méthode Post
        $form = $this->app["form.factory"]->create(new VoitureByServiceForm($services, $vehicules,"create"), $voiture, array(
            'method' => $request->getMethod(),
        ));
        //Récupération des données si le formulaire à déjà été passé
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->app['orm.ems']['gestionReservationAuto']->persist($voiture);
            $this->app['orm.ems']['gestionReservationAuto']->flush();
            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été enregistrées.'
            );
            return $this->app->redirect(
                            $this->app['url_generator']->generate('gestion-reservation-auto-voiture-service')
            );
        }
        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/voiture/createByService.html.twig', array("form" => $form->createView()));
    }

    /**
     * Update voiture
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function updateAction(Request $request, Application $app, $id)
    {
        $repository = $app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Voiture");
        $voiture = $repository->find($id);
        //Si pas en méthode Post
        $so = $this->getGPAUser()->getPerimetre()->getListeServices();
        $vehicules = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Voiture")
                ->findVehicules();
        
        $form = $this->app["form.factory"]->create(new VoitureByServiceForm($so, $vehicules, 'update'), $voiture, array(
            'method' => $request->getMethod(),
        ));
        //Récupération des données si le formulaire à déjà été passé
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->app['orm.ems']['gestionReservationAuto']->persist($voiture);
            $this->app['orm.ems']['gestionReservationAuto']->flush();
            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été modifiées.'
            );
            return $this->app->redirect(
                            $this->app['url_generator']->generate('gestion-reservation-auto-voiture-service')
            );
        }
        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/voiture/updateByService.html.twig', array("form" => $form->createView(), "idVoiture" => $voiture->getIdVoiture()));
    }

    /**
     * Delete voiture
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function deleteAction(Request $request, Application $app, $id)
    {
        $voiture = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Voiture")
                ->findOneByIdVoiture($request->get('id'));
        $this->app['orm.ems']['gestionReservationAuto']->remove($voiture);
        $this->app['orm.ems']['gestionReservationAuto']->flush();

        $this->app['session']->getFlashBag()->add(
                'success', 'La voiture a bien été supprimé.'
        );
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-voiture-service')
        );
    }

}
