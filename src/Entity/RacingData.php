<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// Used to hold raw data against a race_id, when a
/**
 * Raw Data of a Race
 * @ORM\Entity
 */
#[ApiResource]
class RacingData
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
    private string $fullName = '';

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Choice(choices={"medium", "long"})
     */
    private $raceDistance;

    /**
     * @ORM\Column(type="string", length=8))
     * @Assert\Regex(
     *     pattern="/^(([0-9]|[0-9])|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/",
     *     message="The time format should be 'h:mm:ss'"
     * )
     */
    private string $raceTime;

    /** 
     * The age Category of the racer 
     * @ORM\Column
     */    
    private string $ageCategory = '';

    /**
     * Race to which this record belongs to
     * 
     * @ORM\ManyToOne(targetEntity="Race", inversedBy="races")
     */
    private Race $race;

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
    
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getRaceDistance(): ?string
    {
        return $this->raceDistance;
    }

    public function setRaceDistance(string $raceDistance): self
    {
        $this->raceDistance = $raceDistance;

        return $this;
    }

    public function getRaceTime(): ?string
    {
        return $this->raceTime;
    }

    public function setRaceTime(string $raceTime): self
    {
        $this->raceTime = $raceTime;

        return $this;
    }

    public function getAgeCategory(): ?string
    {
        return $this->ageCategory;
    }

    public function setAgeCategory(string $ageCategory): self
    {
        $this->ageCategory = $ageCategory;

        return $this;
    }

    public function getRace(): Race
    {
        return $this->race;
    }

    public function setRace(Race $race): self
    {
        $this->race = $race;

        return $this;
    }

        
}