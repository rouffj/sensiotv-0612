<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OmdbApi;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie", name="movie")
     */
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }
    
    /**
     * @Route("/movie/{id}", name="movie_show", requirements={"id": "\d+"})
     */
    public function show(Movie $movie)
    {
        $this->denyAccessUnlessGranted('MOVIE_SHOW', $movie);
        
        return $this->render('movie/show.html.twig', [
            'movie' => $movie
        ]);
        //return new Response('Fiche du film ' . $id);
    }

    /**
     * @Route("/movie/{imdbId}/import", name="movie_import", requirements={"id": "tt\d+"})
     */
    public function import($imdbId, OmdbApi $omdbApi, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'You should be an ADMIN to have access to the import page');
        
        $movieData = $omdbApi->requestOneById($imdbId);
        $movie = Movie::fromApi($movieData);

        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->redirectToRoute('movie_latest');
    }

    /**
     * @Route("/movie/latest", name="movie_latest")
     */
    public function latest(MovieRepository $movieRepository)
    {
        $movieLimit = 10;
        $movies = $movieRepository->findBy([], ['releaseDate' => 'DESC'], $movieLimit);

        return $this->render('movie/latest.html.twig', [
            'movies' => $movies,
        ]);
    }
    
    /**
     * @Route("/movie/search", name="movie_search")
     */
    public function search(OmdbApi $omdbApi, Request $request)
    {
        $keyword = $request->query->get('keyword', 'Sky');
        $movies = $omdbApi->requestAllBySearch($keyword);
        dump($movies);

        return $this->render('movie/search.html.twig', [
            'movies' => $movies['Search'],
            'keyword' => $keyword,
        ]);
    }
}
