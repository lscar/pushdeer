<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\PushDeerMessage
 *
 * @property integer $id
 * @property string $uid
 * @property string $text
 * @property string $desp
 * @property string $type
 * @property string $readkey
 * @property string|null $url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $pushkey_name
 *
 * @property PushDeerUser $user
 * @property PushDeerKey $key
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage whereDesp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage wherePushkeyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage whereReadkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushDeerMessage whereUrl($value)
 * @mixin Eloquent
 */
class PushDeerMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'readkey',
        'pushkey_name',
        'text',
        'desp',
        'uid',
        'type',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PushDeerUser::class, 'uid', 'id');
    }

    public function key(): BelongsTo
    {
        return $this->belongsTo(PushDeerKey::class, 'pushkey_name', 'key');
    }
}
