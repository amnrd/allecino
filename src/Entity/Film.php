<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilmRepository")
 */
class Film
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="films")
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="film", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bookmark", mappedBy="films")
     */
    private $bookmarks;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function getPositiveVotes(): Collection
    {
      return $this->getVotes()->filter(function (Vote $vote) {
        return $vote->getValue();
      });
    }

    public function getNegativeVotes(): Collection
    {
      return $this->getVotes()->filter(function (Vote $vote) {
        return !$vote->getValue();
      });
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setFilms($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getFilms() === $this) {
                $vote->setFilms(null);
            }
        }

        return $this;
    }

    /**
     *
     * @param User $user
     * @return boolean
     */
    public function isFavoriteByUser(User $user) : bool
    {
      foreach($this->bookmarks as $bookmarks)
      {
        if($bookmarks->getUsers() === $user) return true;
      }

      return false;
    }

    /**
     *
     * @param User $user
     * @return boolean
     */
    public function isLikedByUser(User $user) : bool
    {
      foreach($this->votes as $votes)
      {
        if($votes->getUsers() === $user) return true;
      }

      return false;
    }

    /**
     *
     * @param User $user
     * @return boolean
     */
    public function isDislikedByUser(User $user) : bool
    {
      foreach($this->votes as $votes)
      {
        if($votes->getUsers() === $user) return false;
      }

      return true;
    }


    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFilms($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getFilms() === $this) {
                $comment->setFilms(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bookmark[]
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setFilms($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->contains($bookmark)) {
            $this->bookmarks->removeElement($bookmark);
            // set the owning side to null (unless already changed)
            if ($bookmark->getFilms() === $this) {
                $bookmark->setFilms(null);
            }
        }

        return $this;
    }
}
