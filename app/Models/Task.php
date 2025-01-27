<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status_id',
        'created_by_id',
        'assigned_to_id',
//        'labels[]'
    ];

    /** Статус задачи
     * @return HasOne
     */
    public function taskStatus(): hasOne
    {
        return $this->hasOne(TaskStatus::class, 'id', 'status_id');
    }


    /** Пользователь исполнитель задачи
     * @return HasOne
     */
    public function executor(): hasOne
    {
        return $this->hasOne(User::class, 'id', 'assigned_to_id');
    }

    /** Пользователь автор задачи
     * @return HasOne
     */
    public function author(): hasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }
}
