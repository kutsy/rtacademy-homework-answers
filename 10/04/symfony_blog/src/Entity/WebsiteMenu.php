<?php

namespace App\Entity;

use App\Repository\WebsiteMenuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebsiteMenuRepository::class)
 */
class WebsiteMenu
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
     * @ORM\Column(type="string", length=255)
     */
    private $href;

    /**
     * @ORM\Column(type="smallint")
     */
    private $order_position;

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

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function getOrderPosition(): ?int
    {
        return $this->order_position;
    }

    public function setOrderPosition(int $order_position): self
    {
        $this->order_position = $order_position;

        return $this;
    }
}
