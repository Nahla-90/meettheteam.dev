<?php

namespace App\Entity;

use App\Repository\ColleagueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ColleagueRepository::class)
 * @Vich\Uploadable
 */
class Colleague
{
    public function __construct()
    {
        $this->image = new EmbeddedFile();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^\w+/")
     * @Assert\Length(
     *      max = 30
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $Name;

    /**
     * @Assert\Regex("/^\w+/")
     * @Assert\Length(
     *      max = 300
     * )
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $role;

    /**
     * @Assert\Regex("/^\w+/")
     * @Assert\Length(
     *      max = 300
     * )
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $notes;
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Vich\UploadableField(mapping="colleague_image", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", dimensions="image.dimensions")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     * @var EmbeddedFile
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @param File|UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null)
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage(EmbeddedFile $image): void
    {
        $this->image = $image;
    }

    public function getImage(): ?EmbeddedFile
    {
        return $this->image;
    }
}
