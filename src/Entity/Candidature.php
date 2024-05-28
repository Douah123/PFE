<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $Niveau = null;

    #[ORM\Column(length: 255)]
    private ?string $Ville = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $LettreMotivation = null;

    #[Vich\UploadableField(mapping: 'candidature_image', fileNameProperty: 'LettreMotivation')]
    private ?File $imageFileLettreMotivation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CV = null;

    #[Vich\UploadableField(mapping: 'candidature_image', fileNameProperty: 'CV')]
    private ?File $imageFileCV = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    private ?Job $offer = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    private ?User $user_ = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;


   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->Niveau;
    }

    public function setNiveau(string $Niveau): static
    {
        $this->Niveau = $Niveau;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): static
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getLettreMotivation(): ?string
    {
        return $this->LettreMotivation;
    }

    public function setLettreMotivation(?string $LettreMotivation): static
    {
        $this->LettreMotivation = $LettreMotivation;

        return $this;
    }

    public function getCV(): ?string
    {
        return $this->CV;
    }

    public function setCV(?string $CV): static
    {
        $this->CV = $CV;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user_;
    }

    // Setter for utilisateur
    public function setUser(?User $user): self
    {
        $this->user_ = $user;

        return $this;
    }


    public function getOffer(): ?Job
    {
        return $this->offer;
    }

    public function setOffer(?Job $offer): static
    {
        $this->offer = $offer;

        return $this;
    }

    public function setImageFileCV(?File $imageFile = null): void
    {
        $this->imageFileCV = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFileCV(): ?File
    {
        return $this->imageFileCV;
    }

    public function setImageFileLettreMotivation(?File $imageFile = null): void
    {
        $this->imageFileLettreMotivation = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFileLettreMotivation(): ?File
    {
        return $this->imageFileLettreMotivation;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

}
