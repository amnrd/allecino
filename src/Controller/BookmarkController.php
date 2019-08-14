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
use App\Repository\BookmarkRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Bookmark;
use App\Entity\Film;
use App\Entity\User;

class BookmarkController extends AbstractController
{
    /**
     * @Route("/films/{id}/bookmark", name="bookmark")
     */
    public function bookmark(Film $film, UserInterface $user, Request $request, ObjectManager $manager, BookmarkRepository $repo) : Response
    {
      if($film->isFavoriteByUser($user))
      {
        $bookmark = $repo->findOneBy(
          [
            'films' => $film,
            'users' => $user
          ]);

        $manager->remove($bookmark);
        $manager->flush();

        return $this->redirectToRoute('film_show',
        [
          'id' => $film->getId()
        ]);
      }

      $bookmark = new Bookmark();
      $bookmark->setFilms($film)
               ->setUsers($user);

      $manager->persist($bookmark);
      $manager->flush();


      return $this->redirectToRoute('film_show',
      [
        'id' => $film->getId()
      ]);
    }

    /**
     * @Route("/members/mybookmarks", name="mybookmarks")
     */
     public function myBookmarks(UserInterface $user)
     {
       return $this->render('bookmark/mybookmarks.html.twig',
       [
         'bookmarks' => $user->getBookmarks(),
       ]);
     }
}
