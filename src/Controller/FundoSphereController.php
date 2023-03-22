<?php

namespace App\Controller;

use App\Entity\Fundraising;
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
        $fundraisers = $this->em->getRepository(Fundraising::class)->findAll();

        return $this->render('fundo_sphere/index.html.twig', [
            'fundraisers' => $fundraisers
        ]);
    }

    #[Route('/product', name: 'create_product')]
    public function createProduct(): Response
    {   /*
        $i = 6;
        $product = new Fundraising();
        $product->setTitle($this->fundraisers[$i]['title']);
        $product->setDescription($this->fundraisers[$i]['description']);
        $product->setGoal($this->fundraisers[$i]['goal']);
        $product->setCollected($this->fundraisers[$i]['collected']);
        $product->setDeadline(new \DateTime($this->fundraisers[$i]['deadline']));
        $product->setCreateDate(new \DateTime("2023-03-20"));*/



        return new Response('Saved new product with id ');
    }
}
