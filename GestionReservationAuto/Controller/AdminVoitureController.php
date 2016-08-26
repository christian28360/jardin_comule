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
use DTC\Modules\GestionReservationAuto\Form\VoitureForm;

/**
 * Default controller of application
 *
 * @author wug870
 */
class AdminVoitureController extends AdministrationController
{

    protected function homeAction(Request $request, Application $app)
    {
        $vehicules = $this->getRepository(
            'gestionReservationAuto', 
            'DTC\Modules\GestionReservationAuto\Entity\Voiture')
                ->findVehicules();
        
        return $this->app['twig']->render(
            'src/GestionReservationAuto/Resources/views/voiture/readall.html.twig', 
            array(
                'vehicules' => $vehicules,
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
        //Si pas en méthode Post
        $form = $this->app["form.factory"]->create(new VoitureForm(), $voiture, array(
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
                            $this->app['url_generator']->generate('gestion-reservation-auto-voiture')
            );
        }
        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/voiture/create.html.twig', array("form" => $form->createView()));
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
        $form = $this->app["form.factory"]->create(new VoitureForm(), $voiture, array(
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
                            $this->app['url_generator']->generate('gestion-reservation-auto-voiture')
            );
        }
        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/voiture/update.html.twig', array("form" => $form->createView(), "idVoiture" => $voiture->getIdVoiture()));
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
        $user = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\User")
                ->findOneByVoitureAttribue($voiture);
        if (!_empty($user)) {
            $this->app['session']->getFlashBag()->add(
                    'danger', 'La voiture est attribué à un utilisateur, suppression impossible.'
            );
            return $this->app->redirect(
                            $this->app['url_generator']->generate('gestion-reservation-auto-voiture')
            );
        }
        $this->app['orm.ems']['gestionReservationAuto']->remove($voiture);
        $this->app['orm.ems']['gestionReservationAuto']->flush();
        $this->app['session']->getFlashBag()->add(
                'success', 'La voiture a bien été supprimé.'
        );
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-voiture')
        );
    }

}
