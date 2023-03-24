<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\PushDeerUser
 *
 * @property integer $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $apple_id
 * @property string|null $wechat_id
 * @property integer $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $simple_token
 *
 * @property Collection $userDevices
 * @property Collection $userKeys
 * @property Collection $userMessages
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser whereAppleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser whereSimpleToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerUser whereWechatId($value)
 * @mixin Eloquent
 */
class PushDeerUser extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'apple_id',
        'wechat_id',
        'level',
    ];

    protected static function booted(): void
    {
        static::created(
            function ($model) {
                /**
                 * @var PushDeerUser $model
                 */
                $model->simple_token = sprintf('SP%dP%s', $model->id, md5(uniqid(rand(), true)));
                $model->save();
            }
        );
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getAuthPassword(): string
    {
        // 框架的auth组件必须使用password字段，兼容处理使用fake password
        return password_hash($this->email ?? '', PASSWORD_DEFAULT);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function userDevices(): HasMany
    {
        return $this->hasMany(PushDeerDevice::class, 'uid', 'id');
    }

    public function userMessages(): HasMany
    {
        return $this->hasMany(PushDeerMessage::class, 'uid', 'id');
    }

    public function userKeys(): HasMany
    {
        return $this->hasMany(PushDeerKey::class, 'uid', 'id');
    }

    public static function getLevelOptions(): array
    {
        return [
            1 => '启用',
            0 => '禁用',
        ];
    }
}
