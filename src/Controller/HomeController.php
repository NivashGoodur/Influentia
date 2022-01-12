<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleOrder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        $articles = $this->entityManager->getRepository(Article::class)->findBy(
            array('premium' => 0),
            array('creation_date' => 'DESC'),
            3,
            0,
        );



        $articles_premium = $this->entityManager->getRepository(Article::class)->findBy(
            array('premium' => 1),
            array('creation_date' => 'DESC'),
            3,
            0,
        );





        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'articles_premium' => $articles_premium
        ]);
    }
}
