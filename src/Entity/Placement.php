<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// Used to hold generated results against a particular Race
/**
 * @ORM\Entity(repositoryClass="App\Repository\PlacementRepository")
 */
#[ApiResource]
class Placement
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
    private string $fullName = '';

    /**
     * @ORM\Column(type="string", length=8))
     * @Assert\Regex(
     *     pattern="/^(([0-9]|[0-9])|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/",
     *     message="The time format should be 'h:mm:ss'"
     * )
     */
    #[Assert\NotBlank]
    private string $finishTime;

    /**
     * Overall Placement
     * @ORM\Column(type="integer")     
     */
    #[Assert\NotBlank]
    private $overAllPlace;

    /**
     * Age Category Placement
     * @ORM\Column(type="integer")     
     */
    #[Assert\NotBlank]
    private $ageCatPlace;

    

    /** 
     * The age Category of the racer 
     * @ORM\Column
     */    
    private string $ageCategory = '';

    /**
     * Race ID to which this result belongs to
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

    public function getfinishTime(): ?string
    {
        return $this->finishTime;
    }

    public function setfinishTime(string $finishTime): self
    {
        $this->finishTime = $finishTime;

        return $this;
    }

    public function getOverAllPlace(): ?string
    {
        return $this->overAllPlace;
    }

    public function setOverAllPlace(string $overAllPlace): self
    {
        $this->overAllPlace = $overAllPlace;

        return $this;
    }

    public function getAgeCatPlace(): ?string
    {
        return $this->ageCatPlace;
    }

    public function setAgeCatPlace(string $ageCatPlace): self
    {
        $this->ageCatPlace = $ageCatPlace;

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