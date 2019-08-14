<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\AbstractType;
use App\Form\FilmType;
use App\Form\UserType;
use App\Repository\FilmRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use App\Entity\Film;
use App\Entity\User;
use App\Entity\Vote;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/film", name="admin_film")
     */
    public function adminFilm(FilmRepository $repo)
    {

        $films = $repo->findAll();

        return $this->render('admin/adminFilm.html.twig',
        [
            'films' => $films
        ]);
    }

    /**
     * @Route("/admin/film/new", name="adminCreate_film")
     * @Route("/admin/film/{id}/edit", name="adminEdit_film")
     */
    public function formFilm(Film $film = null, Request $request, ObjectManager $manager)
    {
      if(!$film)
      {
        $film = new Film();
      }

      $form = $this->createForm(FilmType::class,$film);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid())
      {
        $manager->persist($film);
        $manager->flush();

        return $this->redirectToRoute('film_show',
        [
          'id' => $film->getId()
        ]);
      }

      return $this->render('admin/formFilm.html.twig',
      [
        'formFilm' => $form->createView(),
        'editMod' => $film->getId() !== null
      ]);
    }

    /**
     * @Route("/admin/film/delete/{id}", name="adminDelete_film")
     */
     public function deleteFilm(Request $request, $id)
      {

        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($film);
        $manager->flush();

        $response = new Response();
        $response = send();
      }

      /**
       * @Route("/admin/film/rank", name="adminRank_film")
       */
       public function rankFilm()
       {
         $film = $this->getDoctrine()->getRepository(Film::class)->filmRanking();

         return $this->render('admin/adminRankfilm.html.twig',
         [
           'film' => $film
         ]);
       }

      /**
       * @Route("/admin/user", name="admin_user")
       */
      public function adminUser(UserRepository $repo)
      {

          $users = $repo->findAll();

          return $this->render('admin/adminUser.html.twig',
          [
              'users' => $users
          ]);
      }


      /**
       * @Route("/admin/user/new", name="adminCreate_user")
       * @Route("/admin/user/{id}/edit", name="adminEdit_user")
       */
       public function formUser(User $user = null, Request $request,ObjectManager $manager, UserPasswordEncoderInterface $encoder)
       {
         if(!$user)
         {
           $user = new User();
         }

         $form = $this->createForm(UserType::class,$user);

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid())
         {
           $hash = $encoder->encodePassword($user, $user->getPassword());

           $user->setPassword($hash);

           $manager->persist($user);

           $manager->flush();

           return $this->redirectToRoute('admin_user');
         }

         return $this->render('admin/formUser.html.twig',
         [
           'formUser' => $form->createView(),
           'editMod' => $user->getId() !== null
         ]);

       }


        /**
         * @Route("/admin/user/delete/{id}", name="adminDelete_user")
         */
         public function deleteUser(Request $request, $id)
          {

            $user = $this->getDoctrine()->getRepository(User::class)->find($id);

            $manager = $this->getDoctrine()->getManager();

            $manager->remove($user);

            $manager->flush();

            $response = new Response();

            return $response;
          }

}
