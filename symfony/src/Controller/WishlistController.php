<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class WishlistController extends AbstractController
{

    public const DATA = [
        [
            'id'          => "1",
            'name'        => 'PlayStation 5',
            'description' => 'Ігрова консоль нового поколінн',
            'price'       => '500'
        ],
        [
            'id'          => "2",
            'name'        => 'Nike Air Max',
            'description' => 'Кросівки для повсякденного носіння',
            'price'       => '120'
        ],
        [
            'id'          => "3",
            'name'        => 'iPad Pro',
            'description' => 'Планшет для роботи та малювання',
            'price'       => '999'
        ],
        [
            'id'          => "4",
            'name'        => 'Coffee Machine',
            'description' => 'Кавомашина для дому',
            'price'       => '250'
        ]
    ];

    #[Route('/wishlist', name: 'get_wishlist_items', methods: ['GET'])]
    public function getWishlistItems(): Response
    {
        return new JsonResponse(self::DATA);
    }

    #[Route('/wishlist/{id}', name: 'get_wishlist_item_by_id', methods: ['GET'])]
    public function getWishlistItemById(string $id): Response
    {
        foreach (self::DATA as $item) {
            if ($item['id'] === $id) {
                return new JsonResponse($item);
            }
        }

        return new JsonResponse();
    }

    #[Route('/wishlist', name: 'create_wishlist_item', methods: ['POST'])]
    public function createWishlistItem(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $id = rand(5, 100);

        $data['id'] = $id;

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/wishlist/{id}', name: 'update_wishlist_item', methods: ['PATCH'])]
    public function updateWishlistItem(string $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $oldItem = null;

        foreach (self::DATA as $item) {
            if ($item['id'] === $id) {
                $oldItem = $item;
            }
        }

        if (!$oldItem) {
            throw new NotFoundHttpException();
        }

        if (isset($data['name'])) {
            $oldItem['name'] = $data['name'];
        }

        if (isset($data['description'])) {
            $oldItem['description'] = $data['description'];
        }

        if (isset($data['price'])) {
            $oldItem['price'] = $data['price'];
        }

        return new JsonResponse($oldItem);
    }

    #[Route('/wishlist/{id}', name: 'delete_wishlist_item', methods: ['DELETE'])]
    public function deleteWishlistItem(string $id): Response
    {
        foreach (self::DATA as $item) {
            if ($item['id'] === $id) {
                return new JsonResponse(null, Response::HTTP_NO_CONTENT);
            }
        }

        throw new NotFoundHttpException();
    }

}
