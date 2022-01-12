<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;
use App\Entity\Article;
use App\Entity\ArticleOrder;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }


    /**
     * @Route("/commande/create-session/{id}", name="stripe_create_session")
     */
    public function index($id): Response
    {

        //Redirection sur la page de connexion si l'utilisateur n'est pas connecté 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $article = $this->entityManager->getRepository(Article::class)->find($id);


        //TEST
        $user = $this->security->getUser();
        $userId = $user->getId();

        //Récupération et vérification si l'article a été acheté par l'utilisateur connecté
        $article_order = $this->entityManager->getRepository(ArticleOrder::class)->findOneByArticleId($id, $userId);

        //Si l'article est déjà acheté on revoie l'utilisateur sur la page de l'article
        if ($article_order) {
            return $this->redirectToRoute('all_premium_articles');
        } else {
            $YOUR_DOMAIN = 'http://127.0.0.1:8000';

            Stripe::setApiKey('sk_test_51KGq7dLSDU8vtYVYYiEptU6QPRfwPmD8GuQvAOFdann7TXuSRSpzdIgQTerXq18OTFJLD6RQ1LNCyzeK3TSAXsh900M8vbPIcc');

            $checkout_session = Session::create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'unit_amount' => $article->getPrice(),
                            'product_data' => [
                                'name' => $article->getTitle(),
                            ]
                        ],
                        'quantity' => 1,
                    ],
                ],
                'payment_method_types' => [
                    'card',
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);

            //Enregistre la commande
            $article_order = new ArticleOrder();
            $article_order->setArticle($article);
            $article_order->setUser($this->security->getUser());
            $article_order->setBuy(0);
            $article_order->setCreatedAt(new DateTime());
            $article_order->setStripeSessionId($checkout_session->id);
            $this->entityManager->persist($article_order);
            $this->entityManager->flush();

            return $this->redirect($checkout_session->url);
        }

        
    }
    


    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function orderValidate($stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(ArticleOrder::class)->findOneByStripeSessionId($stripeSessionId);

        //Redirection si ce n'est pas le bon utilisateur
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if (!$order->getBuy()) {
            $order->setBuy(1);
            $this->entityManager->flush();
        }




        return $this->render('order_validate/index.html.twig', [
            'order' => $order
        ]);
    }


    /**
     * @Route("/commande/erreur/{stripeSessionId}", name="order_cancel")
     */
    public function orderCancel($stripeSessionId)
    {
        $order = $this->entityManager->getRepository(ArticleOrder::class)->findOneByStripeSessionId($stripeSessionId);

        //Redirection si ce n'est pas le bon utilisateur
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('order_cancel/index.html.twig');
    }
}
