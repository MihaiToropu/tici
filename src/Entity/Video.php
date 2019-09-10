<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
{
	use TimestampableEntity;

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="videos")
	 * @ORM\JoinColumn(nullable=false)
	 * @Assert\NotBlank(message="you are the author")
	 */
	private $author;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $videoContent;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $publishedAt;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Tutorial", inversedBy="videos")
	 */
	private $Tutorial;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="video", fetch="EXTRA_LAZY")
	 * @ORM\OrderBy({"createdAt" = "DESC"})
	 */
	private $comments;

	/**
	 * @ORM\Column(type="string", length=180, nullable=true)
	 * @Gedmo\Slug(fields={"title"})
	 */
	private $slug;

	/**
	 * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="videos")
	 */
	private $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videoPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagePath;

	public function __construct()
                  	{
                  		$this->comments = new ArrayCollection();
                  		$this->tags = new ArrayCollection();
                  	}


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

	public function getAuthor(): ?User
                  	{
                  		return $this->author;
                  	}

	public function setAuthor(?User $author): self
                  	{
                  		$this->author = $author;
                  
                  		return $this;
                  	}

	public function getVideoContent(): ?string
                  	{
                  		return $this->videoContent;
                  	}

	public function setVideoContent(?string $videoContent): self
                  	{
                  		$this->videoContent = $videoContent;
                  
                  		return $this;
                  	}

	public function getPublishedAt(): ?\DateTimeInterface
                  	{
                  		return $this->publishedAt;
                  	}

	public function setPublishedAt(?\DateTimeInterface $publishedAt): self
                  	{
                  		$this->publishedAt = $publishedAt;
                  
                  		return $this;
                  	}

	public function getTutorial(): ?Tutorial
                  	{
                  		return $this->Tutorial;
                  	}

	public function setTutorial(?Tutorial $Tutorial): self
                  	{
                  		$this->Tutorial = $Tutorial;
                  
                  		return $this;
                  	}

	/**
	 * @return Collection|Comment[]
	 */
	public function getExistingComments(): Collection
                  	{
                  		$criteria = VideoRepository::existingCommentsCriteria();
                  
                  		return $this->comments->matching($criteria);
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
                  			$comment->setVideo($this);
                  		}
                  
                  		return $this;
                  	}

	public function removeComment(Comment $comment): self
                  	{
                  		if ($this->comments->contains($comment)) {
                  			$this->comments->removeElement($comment);
                  			// set the owning side to null (unless already changed)
                  			if ($comment->getVideo() === $this) {
                  				$comment->setVideo(null);
                  			}
                  		}
                  
                  		return $this;
                  	}

	public function getSlug(): ?string
                  	{
                  		return $this->slug;
                  	}

	public function setSlug(string $slug): self
                  	{
                  		$this->slug = $slug;
                  
                  		return $this;
                  	}

	/**
	 * @return Collection|Tag[]
	 */
	public function getTags(): Collection
                  	{
                  		return $this->tags;
                  	}

	public function addTag(Tag $tag): self
                  	{
                  		if (!$this->tags->contains($tag)) {
                  			$this->tags[] = $tag;
                  		}
                  
                  		return $this;
                  	}

	public function removeTag(Tag $tag): self
                  	{
                  		if ($this->tags->contains($tag)) {
                  			$this->tags->removeElement($tag);
                  		}
                  
                  		return $this;
                  	}

    public function getVideoPath(): ?string
    {
        return $this->videoPath;
    }

    public function setVideoPath(?string $videoPath): self
    {
        $this->videoPath = $videoPath;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

	public function __toString(): string
	{
		return $this->title;
	}


	/*public function validate(ExecutionContextInterface $context, $payload)
	{
		if (stripos($this->getTitle(),'fuc') !== false) {
			$context->buildViolation('you can use that type of words here...')
				->atPath('title')
				->addViolation()
			;
		}
	}*/
}
