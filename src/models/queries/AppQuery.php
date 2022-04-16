<?php

namespace ddruganov\Yii2ApiAuth\models\queries;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiEssentials\traits\Sorting;
use yii\db\ActiveQuery;

final class AppQuery extends ActiveQuery
{
    use Sorting;

    public function __construct(array $config = [])
    {
        parent::__construct(App::class, $config);
    }

    /**
     * @return App[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    public function one($db = null): App|array|null
    {
        return parent::one($db);
    }
}
