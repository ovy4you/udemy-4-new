<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $text;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $time;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProfileUser", inversedBy="microPosts")
     */
    private $user;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }


    public function getUser(): ?ProfileUser
    {
        return $this->user;
    }

    public function setUser(?ProfileUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    /** @ORM\PrePersist() */
    public function setTimeOnPersist()
    {
        $this->time = new \DateTime();
    }
}
