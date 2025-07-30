<?php

namespace App\Controller;

use App\Dto\ProductDto;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/ware/house/api')]
final class WareHouseAPIController extends AbstractController
{
    #[Route(name: 'app_product_api_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $data = [
            'products' => $productRepository->findAll(),
            'success' => 1,
        ];
        return new JsonResponse($data);
    }

    #[Route('/{id}', name: 'app_product_api_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        $data = [
            'product' => $product,
            'success' => 1,
        ];
        return new JsonResponse($data);
    }

    #[Route('/new', name: 'app_product_api_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $productDto = new ProductDto();
        $productDto->name = $data['name'] ?? '';
        $productDto->num = $data['num'] ?? '';
        $errors = $validator->validate($productDto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages, 'success' => 0], 400);
        }
        $product = new Product();
        $product->setName($productDto->name);
        $product->setNum($productDto->num);
        $entityManager->persist($product);
        $entityManager->flush();
        $data = [
            'name' => $productDto->name,
            'num' => $productDto->num,
            'success' => 1,
        ];
        return new JsonResponse($data);
    }

    #[Route('/{id}/edit', name: 'app_product_api_edit', methods: ['POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);
        $productDto = new ProductDto();
        $productDto->name = $data['name'] ?? '';
        $productDto->num = $data['num'] ?? '';
        $errors = $validator->validate($productDto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages, 'success' => 0], 400);
        }
        $product->setName($productDto->name);
        $product->setNum($productDto->num);
        $entityManager->persist($product);
        $entityManager->flush();
        $data = [
            'name' => $productDto->name,
            'num' => $productDto->num,
            'success' => 1,
        ];
        return new JsonResponse($data);
    }
}
