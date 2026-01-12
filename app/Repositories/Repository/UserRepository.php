<?php

namespace App\Repositories\Repository;

use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    public function getUsers(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $name = isset($filters['q']) ? trim((string) $filters['q']) : '';

        $query = User::query()
            ->when($name !== '', function ($q) use ($name) {
                $like = '%' . $name . '%';
                $q->where('full_name', 'like', $like)
                    ->orWhere('email', 'like', $like);
            })
            ->latest();

        return $query->paginate($perPage);
    }

    public function getDetail(int $id): ?User
    {
        return User::find($id);
    }

    public function lockUser(int $id): bool
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }

        return $user->update(['status' => 0]);
    }
}
