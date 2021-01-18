<?php

namespace App\Command;

use App\Entity\TvShow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoadTvshowRatingCommand extends Command
{
    protected static $defaultName = 'app:load-tvshow-rating';

    private $manager;
    private $client;

    public function __construct(EntityManagerInterface $manager, HttpClientInterface $client)
    {
        $this->manager = $manager;
        $this->client = $client;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Récupération des notes des séries OMDB API');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $apikey = "290595bb";
        $apiUrl = "http://www.omdbapi.com/";

        $io = new SymfonyStyle($input, $output);

        $tvShowRepo = $this->manager->getRepository(TvShow::class);

        $tvShows = $tvShowRepo->findAll();

        foreach($tvShows as $tvShow)
        {
            $title = $tvShow->getTitle();
            $synopsis = $tvShow->getSynopsis();

            $response = $this->client->request(
                'GET',
                $apiUrl,
                [
                    "query" => [
                        "apikey" => $apikey,
                        "type" => "series",
                        "t" => $title
                    ]
                ]
            );

            $statusCode = $response->getStatusCode();
            if($statusCode != 200) {
                $io->error($title . " : Code erreur " . $statusCode);
                continue;
            }

            $content = $response->toArray();

            if(isset($content['Error'])){
                $io->error($title . " : " . $content['Error']);
                continue;
            }

            $rating = $content['imdbRating'];
            $synopsis = $content['Plot'];
            $poster = $content['Poster'];

            $tvShow->setRating($rating);
            $tvShow->setSynopsis($synopsis);
            $tvShow->setPoster($poster);


        }

        $this->manager->flush();

        

        return Command::SUCCESS;
    }
}
