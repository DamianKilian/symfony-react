<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/ware/house')]
final class WareHouseController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ) {}

    #[Route(name: 'app_product', methods: ['GET'])]
    public function app(): Response
    {
        return $this->render('ware_house/index.html.twig');
    }

    #[Route('/index', name: 'app_product_index', methods: ['GET'])]
    public function index(): Response
    {
        $response = $this->client->request('GET', 'http://app' . $this->generateUrl('app_product_api_index'), [
            'headers' => [
                'x-auth-token' => $this->getParameter('api.auth_token'),
            ],
        ]);
        $data = $response->toArray();

        return new JsonResponse($data);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $response = $this->client->request('GET', 'http://app' . $this->generateUrl('app_product_api_show', ['id' => $data['productId']]), [
            'headers' => [
                'x-auth-token' => $this->getParameter('api.auth_token'),
            ],
        ]);
        $data = $response->toArray();

        return new JsonResponse($data);
    }

    #[Route('/new', name: 'app_product_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $response = $this->client->request('POST', 'http://app' . $this->generateUrl('app_product_api_new'), [
            'json' => ['name' => $data['name'], 'num' => $data['num']],
            'headers' => [
                'x-auth-token' => $this->getParameter('api.auth_token'),
            ],
        ]);
        $data = $response->toArray();

        return new JsonResponse($data);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['POST'])]
    public function edit(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $response = $this->client->request('POST', 'http://app' . $this->generateUrl('app_product_api_edit', ['id' => $id]), [
            'json' => ['name' => $data['name'], 'num' => $data['num']],
            'headers' => [
                'x-auth-token' => $this->getParameter('api.auth_token'),
            ],
        ]);
        $data = $response->toArray();

        return new JsonResponse($data);
    }
}
