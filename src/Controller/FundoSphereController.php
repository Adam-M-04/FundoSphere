<?php

namespace App\Controller;

use App\Entity\Fundraising;
use App\Entity\User;
use App\Form\FundraisingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $fundraisers = $this->em->getRepository(Fundraising::class)->findBy([], ['create_date' => 'DESC'], 6);

        return $this->render('fundo_sphere/index.html.twig', [
            'fundraisers' => $fundraisers
        ]);
    }

    #[Route('/fundraiser', name: 'fundraisers')]
    public function fundraisers(Request $request): Response
    {
        $sort_by = $request->query->get('sort_by');
        $repository = $this->em->getRepository(Fundraising::class);

        switch ($sort_by) {
            case "oldest":
                $fundraisers = $repository->findBy([], ['create_date' => 'ASC']);
                break;
            case "ending":
                $query = $this->em->createQuery("SELECT f FROM App\Entity\Fundraising f WHERE f.deadline >= :now ORDER BY f.deadline")
                    ->setParameter('now', new \DateTime());
                $fundraisers = $query->getResult();
                break;
            case "funds_raised":
                $query = $this->em->createQuery("SELECT f, (SELECT SUM(d.amount) FROM App\Entity\Donation d WHERE d.fundraiser = f.id) as donation_sum  FROM App\Entity\Fundraising f ORDER BY donation_sum DESC");
                $fundraisers = array_map(function ($item) { return $item[0]; }, $query->getResult());
                break;
            default:
                $fundraisers = $repository->findBy([], ['create_date' => 'DESC']);
        }

        return $this->render('fundo_sphere/allFundraisers.html.twig', [
            'fundraisers' => $fundraisers
        ]);
    }
}
