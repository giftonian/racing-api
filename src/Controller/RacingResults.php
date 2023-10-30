<?php

// src/Controller/RacingResults.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Race;

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
    public function uploadRaceResultsCSV(Request $request, SerializerInterface $serializer): JsonResponse
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

        $race->setTitle($race_data['race_title']);
        $race->setRaceDate(new DateTimeImmutable( $race_data['race_date']));
        $this->em->persist($race);
        $this->em->flush();

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

            if (($handle = fopen($csvFilePath, 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    /*$race = new Race();
                    $race->setRaceTitle($race_title);
                    $race->setRaceDate($race_date);
                    $race->setRaceResult($data);
                    $this->em->persist($race);
                    $this->em->flush();*/
                    echo $data[1].'\n';
                }
                fclose($handle);
            }
        }
        
        
        return $this->json([
            'code' => 200,
            'data' => $originalFilename,
        ]);
        
    }

    
}
