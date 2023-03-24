<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\Models\PushDeerDevice
 *
 * @property integer $id
 * @property string $uid
 * @property string $device_id
 * @property string $type
 * @property integer $is_clip
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property PushDeerUser $user
 * @property Collection $userKeys
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice whereIsClip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerDevice whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PushDeerDevice extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'uid',
        'type',
        'device_id',
        'is_clip',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PushDeerUser::class, 'uid', 'id');
    }

    public function userKeys(): HasMany
    {
        return $this->hasMany(PushDeerKey::class, 'uid', 'uid');
    }

    public function routeNotificationForApn(): string
    {
        return $this->device_id;
    }

    public function routeNotificationForApnClip(): string
    {
        return $this->device_id;
    }

    public static function getIsClipOptions(): array
    {
        return [
            1 => '是',
            0 => '否',
        ];
    }
}
