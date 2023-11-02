<?php

// src/Controller/RacingResults.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Race;
use App\Entity\RacingData;
use App\Repository\RaceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Factory\JsonResponseFactory;
use DateTimeImmutable;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Mime\Parser;
use Symfony\Component\Mime\Message;

class RacingResults extends AbstractController
{
    private $em;
    private $raceRepository;
    private $urlGenerator;

    /**
     * __construct
     *
     * @param  mixed $em
     * @return void
     */
    public function __construct(EntityManagerInterface $em, RaceRepository $raceRepository, UrlGeneratorInterface $urlGenerator) 
    {
        $this->em = $em;
        $this->raceRepository = $raceRepository;  
        $this->urlGenerator = $urlGenerator;      
    }

    #[Route('/api/raceslist/{title}', name: 'app_races_list', defaults: ['title' => null], methods: ['GET', 'HEAD'])]    
    /**
     * index
     *
     * @param  mixed $name
     * @return JsonResponse
     */
    public function index($title): JsonResponse
    {
        $repository = $this->em->getRepository(Race::class); 
        $races = $repository->findAll(); 

        // $this->jsonResponseFactory->create($races);
        return $this->json([
            'code' => 200,
            'data' => $races,
        ]);
    }


    #[Route('/api/race-results/upload-csv', name: 'races_upload', methods: ['POST'])]    
    public function uploadRaceResultsCSV(Request $request): JsonResponse
    {
        //var_dump($request->getContent());
        
        // Get the request content (multipart/form-data)
        //$requestContent = $request->getContent();
        
        $race = new Race();
        $race_data = [];
        $race_data['race_title'] = $request->request->get('race_title');
        $race_data['race_date'] = $request->request->get('race_date');
        $csv_data = $request->files->get('csv_data');
        $uploadedFile = $request->files->get('csv_data');
        //dd($csv_data);

        try {

            $this->em->beginTransaction(); // starting the DB Transaction

            $race->setTitle($race_data['race_title']);
            $race->setRaceDate(new DateTimeImmutable( $race_data['race_date']));
            $this->em->persist($race);
            //$this->em->flush();

            $raceId = $race->getId();

            $originalFilename = '';
            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                // Generate a unique name for the file
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

                // Move the file to the specified directory
                $csvDirectory = 'racing_uploads';
                $uploadedFile->move($csvDirectory, $newFilename);

                // Parse the CSV file row by row
                $csvFilePath = $csvDirectory.'/'.$newFilename;

                $fullName = '';
                $raceDistance = '';
                $raceTime = '';
                $ageCategory = '';

                //$racingData = new RacingData();
                if (($handle = fopen($csvFilePath, 'r')) !== false) {
                    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                        $fullName       = $data[0];
                        $raceDistance   = $data[1];
                        $raceTime       = $this->formatRaceTime($data[2]);
                        $ageCategory    = $data[3];

                        $racingData = new RacingData();
                        $racingData->setFullName($fullName);
                        $racingData->setRaceDistance($raceDistance);
                        $racingData->setRaceTime($raceTime);
                        $racingData->setAgeCategory($ageCategory);
                        $racingData->setRace($race);

                        $this->em->persist($racingData);
                        
                    }
                    $this->em->flush();
                    $this->em->commit();
                    fclose($handle);
                }
            }
        } catch (\Exception $e) {
            $this->em->rollback();  
            return $this->json([
                'code' => 401,
                'message' => 'Error occured while processing the CSV file'
            ]);          
        }
        
        
        return $this->json([
            'code' => 200,
            'message' => 'Race Data saved successfully!',
        ]);
        
    } // end function uploadRaceResultsCSV

    #[Route('/api/get-races-collection', name: 'get_races_collection', methods: ['GET'])]    
    public function getRacesCollection(Request $request): JsonResponse
    {
        $racingDataRepository = $this->em->getRepository(Race::class);
        $raceCollections = $racingDataRepository->fetchRaceCollections();
        if (empty($raceCollections)) {
            return $this->json([
                'code' => 401,
                'message' => 'No data found'
            ]);
        }

        return $this->json([
            'code' => 200,
            'data' => $raceCollections,
        ]);
    }

    #[Route('/api/get-race-results', name: 'get_race_results', methods: ['GET'])]    
    public function getRaceResults(Request $request): JsonResponse
    {
        $race_id = 14;
        $racingDataRepository = $this->em->getRepository(RacingData::class);
        $raceResults = $racingDataRepository->fetchRaceResults($race_id);
        if (empty($raceCollections)) {
            return $this->json([
                'code' => 401,
                'message' => 'No data found'
            ]);
        }

        return $this->json([
            'code' => 200,
            'data' => $raceResults,
        ]);
    }


    public function formatRaceTime($raceTime)
    {
        // Split the time into hours, minutes, and seconds
        list($hours, $minutes, $seconds) = explode(":", $raceTime);

        // Use str_pad to add a leading "0" if necessary
        $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
        $seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT);

        // Format the time as "hh:mm:ss"
        $formattedTime = "$hours:$minutes:$seconds";

        return $formattedTime;
    }

    
}
