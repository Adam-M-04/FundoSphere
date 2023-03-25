<?php

namespace App\Controller;

use App\Entity\Fundraising;
use App\Form\FundraisingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FundoSphereController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $fundraisers = $this->em->getRepository(Fundraising::class)->findBy([], ['create_date' => 'DESC'], 4);

        return $this->render('fundo_sphere/index.html.twig', [
            'fundraisers' => $fundraisers
        ]);
    }
}
