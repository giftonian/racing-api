<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * 
 * @ORM\Entity
 */
#[ApiResource]
class Race
{
   
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
    */
    private ?int $id = null;

    /** 
     * The full name of the racer 
     * @ORM\Column
     */    
    private string $title = '';

    /** 
     * The date of the race 
     * @ORM\Column(type="date")
     */        
    private ?\DateTimeInterface  $raceDate = null; // received as date in API request


    /** 
     * The date of the race 
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */ 
    private ?\DateTimeInterface  $createdAt = null;    

    public function __construct()
    {
        $this->createdAt = new \DateTime();        
    }

    
    /**
     * Get the id of single row of racing (raw) data being imported.
     */
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

    public function getRaceDate(): ?\DateTimeInterface
    {
        return $this->raceDate;
    }

    public function setRaceDate(\DateTimeInterface $raceDate): self
    {
        $this->raceDate = $raceDate;

        return $this;
    }
    
}