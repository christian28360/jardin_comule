<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DTC\Modules\GestionReservationAuto\Entity\User;
use DTC\Modules\GestionReservationAuto\Form\UserForm;

/**
 * UserController controller of application
 *
 * @author wug870
 */
class UserController extends GlobalController
{

    /**
     * Create user
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function createAction(Request $request, Application $app)
    {

        $unUser = new User();
        $unUser->setSourceAgent($this->app['gra.sourceAgent']);

        //Si pas en méthode Post
        $form = $this->app["form.factory"]->create(new UserForm(false, null, $unUser), $unUser, array(
            'method' => $request->getMethod(),
        ));
        //Récupération des données si le formulaire à déjà été passé
        $form->handleRequest($request);

        if ($form->isValid()) {
            $unUser->setSourceAgent($this->app['gra.sourceAgent']);
            $unUser->setGrade('utilisateur');

            $this->app['orm.ems']['gestionReservationAuto']->persist($unUser);
            $this->app['orm.ems']['gestionReservationAuto']->flush();

            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été enregistrées'
            );

            return $this->app->redirect(
                            $this->app['url_generator']->generate('gestion-reservation-auto-homepage')
            );
        }

        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']->render(
                        'src/GestionReservationAuto/Resources/views/user/profil.html.twig', array(
                    "form" => $form->createView(),
                    "mode" => 'normal',
                    "etat" => 'Création',
        ));
//        //on passe la méthode createView() à la vue pour qu'elle l'affiche
//        return $this->app['twig']->render(
//                        'src/GestionReservationAuto/Resources/views/user/create.html.twig', array(
//                    "form" => $form->createView(),
//                    "mode" => 'normal',
//                    "etat" => 'création',
//        ));
    }

    // fonction non appelée ? à supprimer si oui
//    protected function beAdminAction(Request $request, Application $app)
//    {
//        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\User");
//        $user = $repository->find($this->getGPAUser()->getIdUser());
//        $user->setGrade('admin');
//        $this->app['orm.ems']['gestionReservationAuto']->persist($user);
//        $this->app['orm.ems']['gestionReservationAuto']->flush();
//        return $this->app->redirect(
//                        $this->app['url_generator']->generate('gestion-reservation-auto-user-update-me')
//        );
//    }

    protected function profilAction(Request $request, Application $app)
    {

        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\User");
        $user = $repository->find($this->getGPAUser()->getIdUser());
        $form = $this->app["form.factory"]->create(new UserForm(false, null, $user), $user, array(
            'method' => $request->getMethod(),
        ));

        //Récupération des données si le formulaire à déjà été passé
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->app['orm.ems']['gestionReservationAuto']->persist($user);
            $this->app['orm.ems']['gestionReservationAuto']->flush();
            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été modifiées.'
            );
            return $this->app->redirect(
                            $this->app['url_generator']->generate('gestion-reservation-auto-user-update-me')
            );
        }

        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']
                        ->render('src/GestionReservationAuto/Resources/views/user/profil.html.twig', array("form" => $form->createView(),
                            "idUser" => $user->getIdUser(),
                            "user" => $user,
                            "mode" => 'normal',
                            "etat" => 'Mise à jour',
        ));
    }

}
