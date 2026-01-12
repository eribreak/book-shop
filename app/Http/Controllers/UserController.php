<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interface\UserRepositoryInterface;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function getList(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'q' => $request->query('q', ''),
        ];

        $users = $this->userRepository->getUsers($filters, $perPage);
        $users->appends($request->query());

        return $this->successResponse($users);
    }

    public function getDetail(int $id)
    {
        $user = $this->userRepository->getDetail($id);

        if (!$user) {
            return $this->errorResponse('User not found.');
        }

        return $this->successResponse($user);
    }

    public function lockUser(int $id)
    {
        $result = $this->userRepository->lockUser($id);

        if (!$result) {
            return $this->errorResponse('User not found');
        }

        return $this->successResponse('User locked successfully.');
    }
}
