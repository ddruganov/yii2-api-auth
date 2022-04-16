<?php

namespace ddruganov\Yii2ApiAuth\models\token\queries;

use ddruganov\Yii2ApiAuth\models\token\RefreshToken;
use ddruganov\Yii2ApiEssentials\DateHelper;
use ddruganov\Yii2ApiEssentials\traits\Sorting;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;

final class RefreshTokenQuery extends ActiveQuery
{
    use Sorting;

    public function __construct(array $config = [])
    {
        parent::__construct(RefreshToken::class, $config);
    }

    /**
     * @return RefreshToken[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    public function one($db = null): RefreshToken|array|null
    {
        return parent::one($db);
    }

    public function byUserId(int|array|QueryInterface $value)
    {
        return $this
            ->andWhere([
                'user_id' => $value
            ]);
    }

    public function byAppUuid(string|array|QueryInterface $value)
    {
        return $this
            ->andWhere([
                'app_uuid' => $value
            ]);
    }

    public function expired(bool $value)
    {
        $operator = $value ? '<' : '>';
        return $this
            ->andWhere([
                $operator, 'expires_at', DateHelper::now()
            ]);
    }
}
