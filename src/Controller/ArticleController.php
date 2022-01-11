<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleOrder;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }



    /**
     * @Route("/all-articles", name="all_articles")
     */
    public function showArticles(Request $request, PaginatorInterface $paginator): Response
    {


        // On récupère dans l'url la données GET page (si elle n'existe pas, la valeur retournée par défaut sera la page 1)
        $requestedPage = $request->query->getInt('page', 1);

        // Si le numéro de page demandé dans l'url est inférieur à 1, erreur 404
        if ($requestedPage < 1) {
            throw new NotFoundHttpException();
        }


        // Création d'une requête qui servira au paginator pour récupérer les articles de la page courante
        $query = $this->entityManager->createQuery('SELECT a FROM App\Entity\Article a WHERE a.premium = 0');



        // On stocke dans $pageArticles les 10 articles de la page demandée dans l'URL
        $pageArticles = $paginator->paginate(
            $query,    // Requête de selection des articles en BDD
            $requestedPage,     // Numéro de la page dont on veux les articles
            10      // Nombre d'articles par page
        );




        return $this->render('article/all_articles.html.twig', [
            'articles' => $pageArticles,
        ]);
    }



    /**
     * @Route("/all-premium-articles", name="all_premium_articles")
     */
    public function showPremiumArticles(Request $request, PaginatorInterface $paginator): Response
    {


        // On récupère dans l'url la données GET page (si elle n'existe pas, la valeur retournée par défaut sera la page 1)
        $requestedPage = $request->query->getInt('page', 1);

        // Si le numéro de page demandé dans l'url est inférieur à 1, erreur 404
        if ($requestedPage < 1) {
            throw new NotFoundHttpException();
        }


        // Création d'une requête qui servira au paginator pour récupérer les articles de la page courante
        $query = $this->entityManager->createQuery('SELECT a FROM App\Entity\Article a WHERE a.premium = 1');



        // On stocke dans $pageArticles les 10 articles de la page demandée dans l'URL
        $pageArticles = $paginator->paginate(
            $query,    // Requête de selection des articles en BDD
            $requestedPage,     // Numéro de la page dont on veux les articles
            10      // Nombre d'articles par page
        );




        return $this->render('article/all_premium_articles.html.twig', [
            'articles' => $pageArticles,
        ]);
    }



    /**
     * @Route("/article/{id}", name="article")
     */
    public function showArticle($id): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);




        return $this->render('article/article.html.twig', [
            'article' => $article,
        ]);
    }


    /**
     * @Route("/article/premium/{id}", name="article_premium")
     */
    public function showArticlePremium($id): Response
    {

        //Redirection sur la page de connexion si l'utilisateur n'est pas connecté 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');



        $user = $this->security->getUser();
        $userId = $user->getId();

        //Récupération et vérification que l'article a bien été acheté par l'utilisateur connecté
        $article_order = $this->entityManager->getRepository(ArticleOrder::class)->findOneByArticleId($id, $userId);

        if ($article_order) {
            $article = $this->entityManager->getRepository(Article::class)->find($id);
        } else {
            $article = null;
        }


        return $this->render('article/article_premium.html.twig', [
            'article' => $article,
            'article_order' => $article_order,
        ]);
    }
}
