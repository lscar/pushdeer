<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\PushDeerKey
 *
 * @property integer $id
 * @property string $name
 * @property string $uid
 * @property string $key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property PushDeerUser $user
 * @property Collection $userDevices
 * @property Collection $userMessages
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerKey whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerKey whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerKey whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerKey whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PushDeerKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'key',
        'name',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PushDeerUser::class, 'uid', 'id');
    }

    public function userDevices(): HasMany
    {
        return $this->hasMany(PushDeerDevice::class, 'uid', 'uid');
    }

    public function userMessages(): HasMany
    {
        return $this->hasMany(PushDeerMessage::class, 'pushkey_name', 'key');
    }
}
