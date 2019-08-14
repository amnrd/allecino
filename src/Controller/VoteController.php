<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\FilmRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Vote;
use App\Entity\Like;
use App\Entity\Film;
use App\Entity\User;

class VoteController extends AbstractController
{
  /**
   * @Route("/films/{id}/vote", name="vote")
   */
  public function vote(Film $film,UserInterface $user, Request $request, ObjectManager $manager, VoteRepository $repo) : Response
  {
    if($film->isLikedByUser($user))
    {
      $vote = $repo->findOneBy(
        [
          'films' => $film,
          'users' => $user
        ]);

      $manager->remove($vote);
      $manager->flush();

      return $this->redirectToRoute('film_show',
      [
        'id' => $film->getId()
      ]);
    }

    $vote = new Vote();
    $vote->setFilms($film)
         ->setUsers($user)
         ->setValue(true)
         ;

    $manager->persist($vote);
    $manager->flush();


    return $this->redirectToRoute('film_show',
    [
      'id' => $film->getId()
    ]);
  }

  /**
   * @Route("/films/{id}/unvote", name="unvote")
   */
  public function unvote(Film $film,UserInterface $user, Request $request, ObjectManager $manager, VoteRepository $repo) : Response
  {
    if($film->isLikedByUser($user))
    {
      $vote = $repo->findOneBy(
        [
          'films' => $film,
          'users' => $user
        ]);

      $manager->remove($vote);
      $manager->flush();

      return $this->redirectToRoute('film_show',
      [
        'id' => $film->getId()
      ]);
    }

    $vote = new Vote();
    $vote->setFilms($film)
         ->setUsers($user)
         ->setValue(false)
         ;

    $manager->persist($vote);
    $manager->flush();


    return $this->redirectToRoute('film_show',
    [
      'id' => $film->getId()
    ]);
  }
}
