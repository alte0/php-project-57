<?php

namespace AppSite\Infrastructure;

use App\Models\User;

class RandomUserId
{
    /** Получаем случайного пользователя, исключая переданного
     * @param int $userId
     * @return int
     */
    public function getId(int $userId): int
    {
        $userArr = User::query()
            ->where('id', '!=', $userId)
            ->get()
            ->random()
            ->toArray();

        if (!empty($userArr)) {
            return $userArr['id'];
        }

        return 0;
    }
}
