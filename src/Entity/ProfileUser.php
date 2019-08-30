<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class ProfileUser implements UserInterface//, \Serializable
{

    const ROLE_ADMIN = "ROLE_ADMIN";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     */
    private $microPosts;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProfileUser", mappedBy="following")
     */
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProfileUser", inversedBy="followers")
     * @ORM\JoinTable(name="following",
     *     joinColumns={@ORM\JoinColumn(name="user_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="following_user_id",referencedColumnName="id")})
     */
    private $following;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProfileUser", mappedBy="following")
     */
    private $profileUsers;

    public function __construct()
    {
        $this->microPosts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->profileUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getRoles()
    {

        return [
            'ROLE_USER', 'ROLE_ADMIN'
        ];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

    /**
     * @return Collection|MicroPost[]
     */
    public function getMicroPosts(): Collection
    {
        return $this->microPosts;
    }

    public function addMicroPost(MicroPost $microPost): self
    {
        if (!$this->microPosts->contains($microPost)) {
            $this->microPosts[] = $microPost;
            $microPost->setUser($this);
        }

        return $this;
    }

    public function removeMicroPost(MicroPost $microPost): self
    {
        if ($this->microPosts->contains($microPost)) {
            $this->microPosts->removeElement($microPost);
            // set the owning side to null (unless already changed)
            if ($microPost->getUser() === $this) {
                $microPost->setUser(null);
            }
        }

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(self $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
        }

        return $this;
    }

    public function removeFollower(self $follower): self
    {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    public function addFollowing(self $following): self
    {
        if (!$this->following->contains($following)) {
            $this->following[] = $following;
        }

        return $this;
    }

    public function removeFollowing(self $following): self
    {
        if ($this->following->contains($following)) {
            $this->following->removeElement($following);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getProfileUsers(): Collection
    {
        return $this->profileUsers;
    }

    public function addProfileUser(self $profileUser): self
    {
        if (!$this->profileUsers->contains($profileUser)) {
            $this->profileUsers[] = $profileUser;
            $profileUser->addFollowing($this);
        }

        return $this;
    }

    public function removeProfileUser(self $profileUser): self
    {
        if ($this->profileUsers->contains($profileUser)) {
            $this->profileUsers->removeElement($profileUser);
            $profileUser->removeFollowing($this);
        }

        return $this;
    }

//    public function serialize()
//    {
//        return [
//            $this->id,
//            $this->username,
//            $this->password,
//        ];
//    }
//
//    public function unserialize($serialized)
//    {
//        list($this->id,
//            $this->username,
//            $this->password) = unserialize($serialized);
//    }
}
