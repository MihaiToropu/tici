<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="There's already an account assigned to that mail address!"
 * )
 */
class User implements UserInterface
{
	use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
	 * @Assert\NotBlank(message="You should have a mail in 2019")
	 * @Assert\Email(message="mails have @ in them")
	 * @Groups("user_group_email")
	 */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Groups("userInfo")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TokenApi", mappedBy="user", orphanRemoval=true)
     */
    private $tokenApis;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="author")
     */
    private $videos;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Profile", inversedBy="user", cascade={"persist", "remove"})
     */
    private $userProfile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $agreedTermsAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tutorial", inversedBy="users")
     */
    private $watching;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasCompany;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="user")
     */
    private $company;

    public function __construct()
    {
        $this->tokenApis = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->watching = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
		return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {

    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|TokenApi[]
     */
    public function getTokenApis(): Collection
    {
        return $this->tokenApis;
    }

    public function addTokenApi(TokenApi $tokenApi): self
    {
        if (!$this->tokenApis->contains($tokenApi)) {
            $this->tokenApis[] = $tokenApi;
            $tokenApi->setUser($this);
        }

        return $this;
    }

    public function removeTokenApi(TokenApi $tokenApi): self
    {
        if ($this->tokenApis->contains($tokenApi)) {
            $this->tokenApis->removeElement($tokenApi);
            // set the owning side to null (unless already changed)
            if ($tokenApi->getUser() === $this) {
                $tokenApi->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setAuthor($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getAuthor() === $this) {
                $video->setAuthor(null);
            }
        }

        return $this;
    }

    public function getUserProfile(): ?Profile
    {
        return $this->userProfile;
    }

    public function setUserProfile(?Profile $userProfile): self
    {
        $this->userProfile = $userProfile;

        return $this;
    }

    public function getAgreedTermsAt(): ?\DateTimeInterface
    {
        return $this->agreedTermsAt;
    }

    public function agreeTerms(): self
    {
        $this->agreedTermsAt = new \DateTime();

        return $this;
    }

    public function __toString()
	{
		return $this->getFirstName();
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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tutorial[]
     */
    public function getWatching(): Collection
    {
        return $this->watching;
    }

    public function addWatching(Tutorial $watching): self
    {
        if (!$this->watching->contains($watching)) {
            $this->watching[] = $watching;
        }

        return $this;
    }

    public function removeWatching(Tutorial $watching): self
    {
        if ($this->watching->contains($watching)) {
            $this->watching->removeElement($watching);
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getHasCompany()
	{
		return $this->hasCompany;
	}

	/**
	 * @param mixed $hasCompany
	 */
	public function setHasCompany($hasCompany): void
	{
		$this->hasCompany = $hasCompany;
	}
}
