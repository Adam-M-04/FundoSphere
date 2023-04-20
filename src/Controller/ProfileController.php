<?php

namespace App\Controller;

use App\Entity\FavoriteFundraisers;
use App\Entity\Fundraising;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class ProfileController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/profile', name: 'user_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/profile/fundraisers', name: 'user_fundraisers')]
    public function userFundraisers(): Response
    {
        $fundraisers = $this->em->getRepository(Fundraising::class)->findBy(
            ['fundraiserUser' => $this->getUser() ], ['create_date' => 'DESC']
        );

        return $this->render('profile/userFundraisers.html.twig', [
            'fundraisers' => $fundraisers
        ]);
    }

    #[Route('/profile/fundraisers/favorite', name: 'user_favorite_fundraisers')]
    public function userFavoriteFundraisers(): Response
    {
        $favorites = $this->em->getRepository(FavoriteFundraisers::class)->findBy(['user' => $this->getUser()]);
        $favoriteIDs = [];

        foreach ($favorites as $favorite) {
            $favoriteIDs[] = $favorite->getFavoriteFundraiser()->getId();
        }

        $fundraisers = $this->em->getRepository(Fundraising::class)->findBy(
            ['id' => $favoriteIDs], ['create_date' => 'DESC']
        );

        return $this->render('profile/userFavoriteFundraisers.html.twig', [
            'fundraisers' => $fundraisers
        ]);
    }
}
