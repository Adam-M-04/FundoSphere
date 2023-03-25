<?php

namespace App\Controller;

use App\Entity\Fundraising;
use App\Form\FundraisingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FundraiserController extends AbstractController
{
    #[Route('/fundraising/create', name: 'create_fundraiser')]
    public function createProduct(Request $request, EntityManagerInterface $em): Response
    {
        $fundraising = new Fundraising();
        $form = $this->createForm(FundraisingFormType::class, $fundraising);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fundraising->setCreateDate(new \DateTime('today'));
            $fundraising->setFundraiserUser($this->getUser());

            $imageFile = $form->get('image_path')->getData();

            // Generate a unique name for the file
            $fileName = uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where images are stored
            $imageFile->move(
                $this->getParameter('image_directory'),
                $fileName
            );

            // Set the image filename on the entity
            $fundraising->setImagePath($fileName);

            // Save the entity to the database
            $em->persist($fundraising);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render ('fundo_sphere/createFundraising.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
