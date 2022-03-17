<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $user_id;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'array')]
    private $tags = [];

    #[ORM\Column(type: 'boolean')]
    private $public;

    #[ORM\Column(type: 'array', nullable: true)]
    private $custons = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $likes = [];

    #[ORM\Column(type: 'boolean')]
    private $comments;

    #[ORM\Column(type: 'boolean')]
    private $expire;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $expire_at;

    #[ORM\Column(type: 'boolean')]
    private $erase;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

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

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getCustons(): ?array
    {
        return $this->custons;
    }

    public function setCustons(?array $custons): self
    {
        $this->custons = $custons;

        return $this;
    }

    public function getLikes(): ?array
    {
        return $this->likes;
    }

    public function setLikes(bool $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getComments(): ?bool
    {
        return $this->comments;
    }

    public function setComments(bool $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getExpire(): ?bool
    {
        return $this->expire;
    }

    public function setExpire(bool $expire): self
    {
        $this->expire = $expire;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeImmutable
    {
        return $this->expire_at;
    }

    public function setExpireAt(?\DateTimeImmutable $expire_at): self
    {
        $this->expire_at = $expire_at;

        return $this;
    }

    public function getErase(): ?bool
    {
        return $this->erase;
    }

    public function setErase(bool $erase): self
    {
        $this->erase = $erase;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
