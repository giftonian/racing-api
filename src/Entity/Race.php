<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filtefr\SearchFilter;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\RaceResults;
use App\Repository\RaceRepository;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceRepository")
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
    #[Assert\NotBlank]
    private string $title = '';

    /** 
     * The date of the race 
     * @ORM\Column(type="date", name="race_date")
     */ 
    #[Assert\NotBlank]       
    private ?\DateTimeInterface  $raceDate = null; // received as date in API request


    /** 
     * The date of the race 
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */ 
    private ?\DateTimeInterface  $createdAt = null;  
    
    /**
     * @var RacingData[] Availble racing raw data for this race
     * 
     * @ORM\OneToMany(
     * targetEntity="RacingData", 
     * mappedBy="race",
     * cascade={"persist", "remove"})
     */
    private iterable $racingData;

    /**
     * @var Placement[] Availble placements/results for this race
     * 
     * @ORM\OneToMany(
     * targetEntity="Placement", 
     * mappedBy="race",
     * cascade={"persist", "remove"})
     */
    private iterable $placementData;
    
    

    public function __construct()
    {
        $this->createdAt = new \DateTime();  
        $this->racingData = new ArrayCollection();
        $this->placementData = new ArrayCollection();  
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

    /**
     * @return RacingData[]
     */
    public function getRacingData(): iterable|ArrayCollection
    {
        return $this->racingData;
    }

    /**
     * @return Placement[]
     */
    public function getPlacementData(): iterable|ArrayCollection
    {
        return $this->placementData;
    }
    
}