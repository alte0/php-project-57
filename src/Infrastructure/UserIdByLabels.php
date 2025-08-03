<?php

namespace AppSite\Infrastructure;

use App\Models\UserLabel;

class UserIdByLabels
{
    /** Получаем пользователей, у которых есть метки, исключая передаваемого
     * @param string|int[] $labels
     * @param int $userId
     * @return array
     */
    public function getUserIdList(array $labels, int $userId): array
    {
        if (empty($labels)) {
            return [];
        }

        $userLabelsId = UserLabel::query()
            ->whereIn('label_id', $labels)
            ->select('user_id')
            ->get()
            ->unique('user_id')
            ->pluck('user_id')
            ->filter(fn($value) => $value !== $userId)
            ->toArray();

        return $userLabelsId;
    }
}
