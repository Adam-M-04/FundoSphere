<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FundoSphereController extends AbstractController
{
    public $fundraisers = array(
        // Fundraiser #1
        array(
        "title" => "Help us build a school in Africa",
        "description" => "We want to build a school in a rural area of Africa to provide education for children who otherwise wouldn't have access to it.",
        "goal" => 5000, // in dollars
        "deadline" => "2023-06-30",
        "user" => "John Smith",
        "collected" => 2250, // in dollars
        ),
            // Fundraiser #2
        array(
        "title" => "Support our local animal shelter",
        "description" => "Our animal shelter needs your help to provide food, shelter, and medical care for abandoned and abused animals.",
        "goal" => 10000, // in dollars
        "deadline" => "2023-05-15",
        "user" => "Jane Doe",
        "collected" => 7920, // in dollars
        ),
            // Fundraiser #3
        array(
        "title" => "Help us fight climate change",
        "description" => "We're a non-profit organization working to reduce carbon emissions and promote sustainable living.",
        "goal" => 20000, // in dollars
        "deadline" => "2023-08-31",
        "user" => "David Lee",
        "collected" => 1300, // in dollars
        ),
            // Fundraiser #4
        array(
        "title" => "Support local arts and culture",
        "description" => "Our organization promotes local artists and cultural events in our community. Help us continue our work!",
        "goal" => 5000, // in dollars
        "deadline" => "2023-07-15",
        "user" => "Sarah Johnson",
        "collected" => 650, // in dollars
        ),
            // Fundraiser #5
        array(
        "title" => "Help us provide clean water to those in need",
        "description" => "Millions of people around the world lack access to clean drinking water. Our organization works to provide water filtration systems to communities in need.",
        "goal" => 15000, // in dollars
        "deadline" => "2023-09-30",
        "user" => "Mark Davis",
        "collected" => 14700, // in dollars
        ),
            // Fundraiser #6
        array(
        "title" => "Support mental health research",
        "description" => "Our organization funds research into mental health disorders and provides resources and support to those affected by these conditions.",
        "goal" => 100000, // in dollars
        "deadline" => "2024-03-31",
        "user" => "Emily Brown",
        "collected" => 117630, // in dollars
        ),
            // Fundraiser #7
        array(
        "title" => "Help us provide affordable housing",
        "description" => "Our organization works to provide affordable housing for low-income families and individuals in our community.",
        "goal" => 25000, // in dollars
        "deadline" => "2023-11-30",
        "user" => "Michael Jones",
        "collected" => 18290, // in dollars
        )
    );

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('fundo_sphere/index.html.twig', [
            'fundraisers' => $this->fundraisers
        ]);
    }
}
