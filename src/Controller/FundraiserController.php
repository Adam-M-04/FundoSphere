<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Entity\FavoriteFundraisers;
use App\Entity\Fundraising;
use App\Form\FundraisingFormType;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FundraiserController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/fundraiser/create', name: 'create_fundraiser')]
    public function createFundraiser(Request $request, EntityManagerInterface $em): Response
    {
        $fundraising = new Fundraising();
        $form = $this->createForm(FundraisingFormType::class, $fundraising);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fundraising->setCreateDate(new \DateTime('today'));
            $fundraising->setFundraiserUser($this->getUser());

            $imageFile = $form->get('image_path')->getData();

            if ($imageFile) {
                // Generate a unique name for the file
                $fileName = uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                // Set the image filename on the entity
                $fundraising->setImagePath($fileName);
            }

            // Save the entity to the database
            $em->persist($fundraising);
            $em->flush();

            return $this->redirectToRoute('user_fundraisers');
        }

        return $this->render ('fundo_sphere/createFundraising.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/fundraiser/update/{id}', name: 'update_fundraiser', condition: "params['id'] > 0")]
    public function updateFundraiser(Int $id, Request $request): Response
    {
        $fundraising = $this->em->getRepository(Fundraising::class)->find($id);
        $form = $this->createForm(FundraisingFormType::class, $fundraising);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image_path')->getData();

            if ($imageFile) {
                // Generate a unique name for the file
                $fileName = uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $previousImagePath = $this->getParameter('image_directory').'/'.$fundraising->getImagePath();

                // Set the image filename on the entity
                $fundraising->setImagePath($fileName);
                $this->em->flush();

                // Delete previous image if exists
                $this->deleteImage($previousImagePath);

                return $this->redirectToRoute('user_fundraisers');
            }
            else {
                $fundraising->setTitle($form->get('title')->getData());
                $fundraising->setDescription($form->get('description')->getData());
                $fundraising->setGoal($form->get('goal')->getData());
                $fundraising->setDeadline($form->get('deadline')->getData());

                $this->em->flush();
                return $this->redirectToRoute('user_fundraisers');
            }
        }

        return $this->render ('fundo_sphere/updateFundraising.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/fundraiser/delete/{id}', name: 'delete_fundraiser', condition: "params['id'] > 0")]
    public function deleteFundraiser(Int $id, Request $request): Response
    {
        $fundraising = $this->em->getRepository(Fundraising::class)->find($id);

        if (!$fundraising) {
            throw $this->createNotFoundException('Fundraiser not found!');
        }

        $ImagePath = $this->getParameter('image_directory').'/'.$fundraising->getImagePath();

        $this->em->remove($fundraising);
        $this->em->flush();

        $this->deleteImage($ImagePath);

        return $this->redirectToRoute('user_fundraisers');
    }

    #[Route('/fundraiser/{id}', name: 'fundraiser', condition: "params['id'] > 0")]
    public function fundraiser(int $id, Request $request): Response
    {
        $fundraiser = $this->em->getRepository(Fundraising::class)->find($id);
        $isFavorite = $this->isFavorite($id);

        return $this->render('fundo_sphere/fundraiserPage.html.twig', [
            'fundraiser' => $fundraiser,
            'isFavorite' => $isFavorite,
            'error' => $request->query->get('error') == "true" ? "true" : "false"
        ]);
    }

    #[Route('/fundraiser/{id}/donate', name: 'fundraiserDonate', condition: "params['id'] > 0")]
    public function fundraiserDonate(int $id, Request $request): Response
    {
        $fundraiser = $this->em->getRepository(Fundraising::class)->find($id);

        $amount = $request->request->get('amount');
        if (is_numeric($amount)) {
            $amount = (int)$amount;
            if ($amount >= 1 and $amount <= 10000) {
                $donation = new Donation();
                $donation->setAmount($amount);
                $donation->setFundraiser($fundraiser);
                $donation->setDonationDate(new \DateTime());
                if ($this->getUser()) {
                    $donation->setDonatingUser($this->getUser());
                }

                $this->em->persist($donation);
                $this->em->flush();

                return $this->render('donation/donationConfirmation.html.twig', [
                    'fundraiser' => $fundraiser,
                    'donation' => $donation
                ]);
            }
        }

        return $this->redirectToRoute('fundraiser', ['id' => $fundraiser->getId(), 'error' => 'true']);
    }

    private function deleteImage($path) {
        if (file_exists($path) and is_file($path)) {
            unlink($path);
        }
    }

    #[Route("/fundraiser/favorite/handler/{id}", name: "favoriteHandler")]
    public function favoriteHandler(int $id): JsonResponse
    {
        $isFavorite = $this->isFavorite($id);

        if ($isFavorite) {
            $currentFavorite = $this->em->getRepository(FavoriteFundraisers::class)->findBy(["user" => $this->getUser()->getId(), "favoriteFundraiser" => $id]);
            if (count($currentFavorite) > 0) {
                $this->em->remove($currentFavorite[0]);
            }
        }
        else {
            $newFavorite = new FavoriteFundraisers();
            $newFavorite->setUser($this->getUser());
            $newFavorite->setFavoriteFundraiser($this->em->getRepository(Fundraising::class)->find($id));
            $this->em->persist($newFavorite);
        }

        try {
            $this->em->flush();
        }
        catch (\Exception $e) { }

        $newVal = $this->isFavorite($id);

        if ($newVal == $isFavorite) {
            return new JsonResponse(["success" => false, "new_value" => $newVal]);
        }
        return new JsonResponse(["success" => true, "new_value" => $newVal]);
    }

    private function isFavorite(int $id): bool
    {
        $user = $this->getUser();
        if (!$user) return false;
        return $this->em->getRepository(FavoriteFundraisers::class)->findBy(["user" => $user->getId(), "favoriteFundraiser" => $id]) ? true : false;
    }
}
