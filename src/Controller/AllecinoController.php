<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\FilmRepository;
use App\Repository\UserRepository;
use App\Entity\Film;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;

class AllecinoController extends AbstractController
{
    /**
     * @Route("/films", name="films")
     * @Security("is_granted('ROLE_USER')")
     */
    public function film(FilmRepository $repo)
    {
        $films = $repo->findAll();

        return $this->render('allecino/film.html.twig',
        [
            'films' => $films
        ]);
    }
    /**
    * @Route ("/films/{id}", name="film_show")
    * @Security("is_granted('ROLE_USER')")
    **/

    public function show(Film $films, Request $request, ObjectManager $manager)
    {

      $comment = new Comment();

      $form = $this->createForm(CommentType::class, $comment);

      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid())
      {
        $comment->setCreatedAt(new \DateTime())
                ->setFilm($films);

        $manager->persist($comment);
        $manager->flush();

        return $this->redirectToRoute('film_show',
        [
          'id' => $films->getId()
        ]);
      }

      return $this->render('allecino/show.html.twig',
      [
        'film' => $films,
        'commentForm' => $form->createView()
      ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()

    {
      return $this->render('allecino/home.html.twig');
    }

    /**
     * @Route("/favoris", name="favoris")
     * @Security("is_granted('ROLE_USER')")
     */

     public function favoris()
     {
       return $this->render('allecino/favoris.html.twig');
     }
}
