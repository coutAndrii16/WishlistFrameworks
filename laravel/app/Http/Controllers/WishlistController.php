<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function response;

class WishlistController extends Controller
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

    /**
     * @return Response
     */
    public function getWishlistItems(): Response
    {
        return response()->json(self::DATA, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return Response
     */
    public function getWishlistItemById(string $id): Response
    {
        foreach (self::DATA as $item) {
            if ($item['id'] === $id) {
                return response()->json($item, Response::HTTP_OK);
            }
        }

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createWishlistItem(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $id = rand(5, 100);

        $data['id'] = $id;

        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return Response
     */
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

        return response()->json($oldItem, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return Response
     */
    public function deleteWishlistItem(string $id): Response
    {
        foreach (self::DATA as $item) {
            if ($item['id'] === $id) {
                return response()->json(null, Response::HTTP_NO_CONTENT);
            }
        }

        throw new NotFoundHttpException();
    }

}
