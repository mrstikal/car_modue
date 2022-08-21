<?php

namespace app\modules\car\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use app\modules\car\models\CarLanguage;
use app\modules\user\models\User;
use yii\db\Expression;

/**
 * Base model for service infos table.
 * Service infos are documents about car accidents, damages etc.
 */
class ServiceInfo extends ActiveRecord
{
    /**
     * Available statuses
     */
    const STATUS_SOLVED = 'solved';
    const STATUS_UNSOLVED = 'unsolved';
    const STATUS_DELETED = 'deleted';

    /**
     * Pagination offset for ajax lazy loading, i.e. num of items per page
     */
    private $paginationOffset = 50;

    /**
     * Actual page for lazy loading.
     *
     * @var integer
     */
    public $page = 0;

    /**
     * Corresponding car id to search for.
     *
     * @var integer
     */
    public $carId = 0;

    /**
     * Service info statuse to search for.
     *
     * @var array
     */
    public $statuses = [self::STATUS_SOLVED, self::STATUS_UNSOLVED];

    /**
     * Record owner to search for.
     *
     * @var integer
     */
    public $ownerId = 0;

    /**
     * Order by query parameter
     *
     * @var array
     */
    public $orderBy = [];

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'service_info';
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['car_id', 'date', 'amount', 'owner'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['car_id', 'date', 'title', 'owner'], 'required']
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'car_id' => 'Odpovídající vozidlo',
            'date' => 'Datum vytvoření hlášení nebo požadavku',
            'title' => 'Název hlášení nebo požadavku',
            'description' => 'Detailnější popis',
            'amount' => 'Finanční náročnost požadavku',
            'status' => 'Stav hlášení nebo požadavku',
            'owner' => 'Zapsal'
        ];
    }

    /**
     * Creates instance of this model
     *
     * @return ServiceInfo
     */
    public static function factory(): ServiceInfo
    {
        return new static();
    }

    /**
     * Returns available service infos statuses
     * status value => status label
     * 
     * @param bool $includeDeleted
     *
     * @return array
     */
    public static function getServiceInfoStatuses($includeDeleted = true): array
    {
        $statuses = [
            static::STATUS_SOLVED => 'Vyřešeno',
            static::STATUS_UNSOLVED => 'Zatím nevyřešeno',
        ];

        if ($includeDeleted) {
            $statuses[static::STATUS_DELETED] = 'Smazáno (lze obnovit)';
        }

        return $statuses;
    }

    /**
     * Returns all service infos.
     *
     * @param integer $page pagination for lazy loading
     * @return array
     */
    public function getAllServiceInfos(int $page = 0): array
    {
        $this->page = $page;
        return $this->getServiceInfos();
    }

    /**
     * Returns service infos for given car.
     *
     * @param integer $carId
     * @param integer $page pagination for lazy loading
     * @return array
     */
    public function getCarServiceInfos(int $carId, int $page = 0): array
    {
        $this->page = $page;
        $this->carId = $carId;
        return $this->getServiceInfos();
    }

    /**
     * Returns service infos for given backend user.
     *
     * @param integer $owner
     * @param integer $page pagination for lazy loading
     * @return array
     */
    public function getOwnerServiceInfos(int $owner, int $page = 0): array
    {
        $this->page = $page;
        $this->owner = $owner;
        return $this->getServiceInfos();
    }

    /**
     * Return all solved service infos
     *
     * @param integer $page pagination for lazy loading
     * @return array
     */
    public function getSolvedServiceInfos(int $page = 0): array
    {
        $this->page = $page;
        $this->statuses = self::STATUS_SOLVED;
        return $this->getServiceInfos();
    }

    /**
     * Return all unsolved service infos
     *
     * @param integer $page pagination for lazy loading
     * @return array
     */
    public function getUnsolvedServiceInfos(int $page = 0): array
    {
        $this->page = $page;
        $this->statuses = self::STATUS_UNSOLVED;
        return $this->getServiceInfos();
    }

    /**
     * Return all deleted service infos for possible resurrection.
     *
     * @param integer $page pagination for lazy loading
     * @return array
     */
    public function getDeletedServiceInfos(int $page = 0): array
    {
        $this->page = $page;
        $this->statuses = self::STATUS_DELETED;
        return $this->getServiceInfos();
    }

    /**
     * General query for service infos model
     *
     * @return array
     */
    private function getServiceInfos(): array
    {
        //query base
        $query = (new Query)
            ->select(static::tableName() . '.*')
            ->addSelect('car.name')
            ->addSelect(new Expression('CONCAT_WS(", ", user.last_name, user.first_name) AS owner'))
            ->from(static::tableName());

        //set orderBy param
        if (empty($this->orderBy)) {
            $query->orderBy(['date' => SORT_DESC]);
        } else {
            $query->orderBy($this->orderBy);
        }

        //join car table
        $query->leftJoin(CarLanguage::tableName() . ' car', 'car.car_id = ' . static::tableName() . '.car_id AND car.language = "' . \Yii::$app->sourceLanguage . '"');

        //join users table
        $query->leftJoin(User::tableName() . ' user', 'user.id = ' . static::tableName() . '.owner');

        //search for car if its id not empty
        if (!empty($this->carId)) $query->andWhere(['car.car_id' => $this->carId]);

        //search for owner if its id not empty
        if (!empty($this->ownerId)) $query->andWhere(['owner' => $this->ownerId]);

        //search for statuses if they aren't deleted
        if (!empty($this->statuses)) $query->andWhere([static::tableName() . '.status' => $this->statuses]);

        $countingQuery = clone $query;
        $count = $countingQuery->count();

        //if lazy loading is set, set offset and limit
        //try to load one more record than pagination offset - we will use it for 'we can load more' detection
        if (!empty($this->page)) $query->offset(($this->page - 1) * $this->paginationOffset)->limit($this->paginationOffset + 1);

        $result = $query->all();

        //if lazy loading is set and detection record exists
        if (count($result) > $this->paginationOffset && !empty($this->page)) {
            //unset detection record
            array_pop($result);
            return ['result' => $result, 'can_load_more' => 1, 'count' => $count];
        }

        //if lazy loading is set and detection record doesn't exists
        if (count($result) <= $this->paginationOffset && !empty($this->page)) {
            return ['result' => $result, 'can_load_more' => 0, 'count' => $count];
        }

        //if no lazy loading, just return query result
        if (empty($this->page)) {
            return $result;
        }
    }

    /**
     * Get all info records owners
     *
     * @return array
     */
    public static function getOwners(): array
    {
        return (new Query)
            ->select(new Expression('DISTINCT owner'))
            ->from(static::tableName())
            ->addSelect(new Expression('CONCAT_WS(", ", user.last_name, user.first_name) AS owner'))
            ->addSelect('user.id')
            ->leftJoin(User::tableName() . ' user', 'user.id = ' . static::tableName() . '.owner')
            ->all();
    }

    /**
     * Finds single service info.
     *
     * @param integer $id
     * @return array
     */
    public static function findSingle(int $id)
    {
        return (new Query)
            ->select(static::tableName() . '.*')
            ->from(static::tableName())
            ->addSelect(new Expression('CONCAT_WS(", ", user.last_name, user.first_name) AS owner_name'))
            ->leftJoin(User::tableName() . ' user', 'user.id = ' . static::tableName() . '.owner')
            ->where([static::tableName() . '.id' => $id])
            ->andWhere([static::tableName() . '.status' => [self::STATUS_SOLVED, self::STATUS_UNSOLVED]])
            ->one();
    }

    /**
     * Finds service infos for given car.
     *
     * @param integer $carId
     * @return array
     */
    public static function findForCar(int $carId)
    {
        $query = (new Query)
            ->select([static::tableName() . '.title', static::tableName() . '.date', static::tableName() . '.description', static::tableName() . '.status', 'user.id'])
            ->from(static::tableName())
            ->addSelect(new Expression('CONCAT_WS(", ", user.last_name, user.first_name) AS owner_name'))
            ->leftJoin(User::tableName() . ' user', 'user.id = ' . static::tableName() . '.owner')
            ->where([static::tableName() . '.car_id' => $carId])
            ->orderBy(['date' => SORT_DESC]);

        $count = $query->count();
        $result = $query->one();

        return ['count' => $count, 'result' => $result];
    }
}
