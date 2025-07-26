<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WarehouseController extends AbstractController
{
    #[Route('/warehouse', name: 'app_warehouse')]
    public function index(): Response
    {
        return $this->render('warehouse/index.html.twig', [
            'controller_name' => 'WarehouseController',
        ]);
    }
}
