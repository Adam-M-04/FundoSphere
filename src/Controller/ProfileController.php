<?php

namespace App\Controller;

use App\Entity\FavoriteFundraisers;
use App\Entity\Fundraising;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    #[Route('/profile/change-email', name: 'user_change_email')]
    public function userChangeEmail(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $new_email = $request->request->get('new_email');
        $confirm_password = $request->request->get('confirm_password');
        $user = $this->getUser();

        if ($hasher->isPasswordValid($user, $confirm_password)) {
            $user->setEmail($new_email);
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('user_profile', ['success' => 'true']);
        }

        return $this->redirectToRoute('user_profile', ['error' => 'password']);
    }

    #[Route('/profile/change-username', name: 'user_change_username')]
    public function userChangeUsername(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $new_username = $request->request->get('new_username');
        $confirm_password = $request->request->get('confirm_password');
        $user = $this->getUser();

        if ($this->em->getRepository(User::class)->findBy(['username' => $new_username])) {
            return $this->redirectToRoute('user_profile', ['error' => 'already_used']);
        }

        if ($hasher->isPasswordValid($user, $confirm_password)) {
            $user->setUsername($new_username);
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('user_profile', ['success' => 'true']);
        }

        return $this->redirectToRoute('user_profile', ['error' => 'password']);
    }

    #[Route('/profile/change-password', name: 'user_change_password')]
    public function userChangePassword(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $new_password = $request->request->get('new_password');
        $new_password_confirm = $request->request->get('new_password_confirm');
        $confirm_password = $request->request->get('confirm_password');
        $user = $this->getUser();

        if ($new_password != $new_password_confirm) {
            return $this->redirectToRoute('user_profile', ['error' => 'password_confirm']);
        }

        if (\strlen($new_password) < 8) {
            return $this->redirectToRoute('user_profile', ['error' => 'password_length']);
        }

        if ($hasher->isPasswordValid($user, $confirm_password)) {
            $user->setPassword($hasher->hashPassword($user, $new_password));
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('user_profile', ['success' => 'true']);
        }

        return $this->redirectToRoute('user_profile', ['error' => 'password']);
    }

    #[Route('/profile/delete-account', name: 'user_delete_account')]
    public function userDeleteAccount(Request $request, UserPasswordHasherInterface $hasher, SessionInterface $session, Security $security): Response
    {
        $confirm_password = $request->request->get('confirm_password');
        $user = $this->getUser();

        if ($hasher->isPasswordValid($user, $confirm_password)) {
            foreach ($user->getDonations() as $donation) {
                $user->removeDonations($donation);
            }
            foreach ($user->getFundraisings() as $fundraising) {
                $ImagePath = $this->getParameter('image_directory').'/'.$fundraising->getImagePath();
                if (file_exists($ImagePath) and is_file($ImagePath)) {
                    unlink($ImagePath);
                }
            }

            $this->em->remove($user);
            $this->em->flush();

            $session->invalidate();
            $security->logout(false);

            return $this->redirectToRoute('homepage');
        }

        return $this->redirectToRoute('user_profile', ['error' => 'password']);
    }
}
