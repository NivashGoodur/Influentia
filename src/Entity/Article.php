<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $premium;

    /**
     * @ORM\OneToMany(targetEntity=ArticleOrder::class, mappedBy="article")
     */
    private $articleOrders;

    public function __construct()
    {
        $this->articleOrders = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getPremium(): ?bool
    {
        return $this->premium;
    }

    public function setPremium(bool $premium): self
    {
        $this->premium = $premium;

        return $this;
    }

    /**
     * @return Collection|ArticleOrder[]
     */
    public function getArticleOrders(): Collection
    {
        return $this->articleOrders;
    }

    public function addArticleOrder(ArticleOrder $articleOrder): self
    {
        if (!$this->articleOrders->contains($articleOrder)) {
            $this->articleOrders[] = $articleOrder;
            $articleOrder->setArticle($this);
        }

        return $this;
    }

    public function removeArticleOrder(ArticleOrder $articleOrder): self
    {
        if ($this->articleOrders->removeElement($articleOrder)) {
            // set the owning side to null (unless already changed)
            if ($articleOrder->getArticle() === $this) {
                $articleOrder->setArticle(null);
            }
        }

        return $this;
    }
}
