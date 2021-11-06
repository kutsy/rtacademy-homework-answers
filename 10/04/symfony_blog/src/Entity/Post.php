<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $alias;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publish_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author_id;

    /**
     * @ORM\ManyToOne(targetEntity=PostCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $category_id;

    /**
     * @ORM\ManyToOne(targetEntity=PostCover::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $cover_id;

    /**
     * @ORM\ManyToOne(targetEntity=PostStatus::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $status_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publish_date;
    }

    public function setPublishDate(\DateTimeInterface $publish_date): self
    {
        $this->publish_date = $publish_date;

        return $this;
    }

    public function getAuthorId(): ?User
    {
        return $this->author_id;
    }

    public function setAuthorId(?User $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getCategoryId(): ?PostCategory
    {
        return $this->category_id;
    }

    public function setCategoryId(?PostCategory $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getCoverId(): ?PostCover
    {
        return $this->cover_id;
    }

    public function setCoverId(?PostCover $cover_id): self
    {
        $this->cover_id = $cover_id;

        return $this;
    }

    public function getStatusId(): ?PostStatus
    {
        return $this->status_id;
    }

    public function setStatusId(?PostStatus $status_id): self
    {
        $this->status_id = $status_id;

        return $this;
    }
}
