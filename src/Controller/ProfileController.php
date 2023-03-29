<?php

namespace App\Controller;

use App\Entity\Fundraising;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
