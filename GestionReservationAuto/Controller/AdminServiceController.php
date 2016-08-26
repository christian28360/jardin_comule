<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DTC\Modules\GestionReservationAuto\Entity\Organization\SourceOrga;
use DTC\Modules\GestionReservationAuto\Entity\Perimetre;
use DTC\Modules\GestionReservationAuto\Form\PerimetreForm;
use DTC\Modules\GestionReservationAuto\Form\ManagePerimetreForm;

/**
 * Default controller of application
 *
 * @author wug870
 */
class AdminServiceController extends AdministrationController
{

    protected function createPerimetreAction(Request $request, Application $app)
    {
        $perimetre = new Perimetre();
        //Si pas en méthode Post
        $form = $this->app["form.factory"]->create(new PerimetreForm(), $perimetre, array(
            'method' => $request->getMethod(),
        ));
        //Récupération des données si le formulaire à déjà été passé
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->app['orm.ems']['gestionReservationAuto']->persist($perimetre);
            $this->app['orm.ems']['gestionReservationAuto']->flush();
            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été enregistrées.'
            );
            return $this->app->redirect(
                            $this->app['url_generator']->generate('gestion-reservation-auto-perimetre-readall')
            );
        }
        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/service/createPerimetre.html.twig', array("form" => $form->createView()));
    }

    protected function managePerimetreAction(Request $request, Application $app)
    {
        //On récupere le périmètre si il est passé dans le $request
        $perimetre = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Perimetre")
                ->findOneBy(array(
            'idPerimetre' => $this->request->get('perimetre'),
        ));

        //Si il n'y a pas de perimetre dans le $request on vas chercher le premier de la liste
        if (is_null($perimetre)) {
            $perimetre = $this->app['orm.ems']['gestionReservationAuto']
                    ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Perimetre")
                    ->findOneBy(
                    array(), array(
                'libelle' => 'ASC',
                    ), 1
            );

            return $this->app->redirect(
                            $this->app['url_generator']->generate(
                                    'gestion-reservation-auto-manage-perimetre', array(
                                'perimetre' => $perimetre->getIdPerimetre(),
                            ))
            );
        }

        $originalServices = new \Doctrine\Common\Collections\ArrayCollection();

        // Create an ArrayCollection of the current Service objects in the database
        foreach ($perimetre->getServices() as $service) {
            $originalServices->add($service);
        }
        
        //On charge le formulaire 
        $form = $this->app["form.factory"]->create(new ManagePerimetreForm($this->app), $perimetre, array(
            'method' => $request->getMethod(),
        ));
        $form->handleRequest($this->request);
        
        $services = $perimetre->getListeServices(true);
        
        if ($form->isSubmitted()) {
            $this->getRepository('gestionReservationAuto', 'DTC\Modules\GestionReservationAuto\Entity\Perimetre')
                    ->initServicesFromList($perimetre);
        }
        

        if ($form->isValid()) {
            
            foreach ($originalServices as $service) {
                if (false === in_array($service->getCode(), $services)) {
                    $perimetre->getServices()->removeElement($service);
                }
            }

            $this->getEntityManager('gestionReservationAuto')->persist($perimetre);
            $this->getEntityManager('gestionReservationAuto')->flush();

            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été enregistrées'
            );
            return $this->app->redirect(
                            $this->app['url_generator']->generate(
                                    'gestion-reservation-auto-manage-perimetre', array(
                                'perimetre' => $perimetre->getIdPerimetre(),
                            ))
            );
        }

        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']
                        ->render('src/GestionReservationAuto/Resources/views/service/managePerimetre.html.twig', array(
                            "form" => $form->createView(),
        ));
    }

    protected function findServiceAction(Request $request, Application $app)
    {
        $regate = $this->app['request']->get('data');
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Organization\SourceOrga");
        //$regate['regate']="251780";
        $leService = $repository->findOneByRegate($regate['regate']);
        $converterS = new \DTC\Common\Kernel\Helpers\ToArrayHelper($leService, true);
        $service = $converterS->toArray(true);
        //var_dump($leService);
        return $this->app->json($service);
    }

    protected function perimetreIndexAction(Request $request, Application $app)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Perimetre");
        $lesPerim = $repository->findAll();
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/service/readAll.html.twig', array("lesPerim" => $lesPerim));
    }

}
