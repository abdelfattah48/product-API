<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $products = $em->getRepository(Product::class)->findAll();
        return $this->json($products);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);

        $em->persist($product);
        $em->flush();

        return $this->json($product, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $product = $em->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        return $this->json($product);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $product = $em->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);

        $em->flush();

        return $this->json($product);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $product = $em->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        $em->remove($product);
        $em->flush();

        return $this->json(['message' => 'Product deleted']);
    }
}
