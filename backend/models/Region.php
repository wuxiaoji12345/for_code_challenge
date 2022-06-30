<?php

namespace backend\models;

class Region extends \common\models\Region
{
    const STATUS_VALID = 1;
    const STATUS_DELETE = 2;

    public function getSiblingRegionByPid($pid = 0)
    {
        $data = $this->find()
            ->select(['id', 'name'])
            ->where([
                'pid' => $pid,
                'status' => self::STATUS_VALID
            ])
            ->asArray()
            ->all();
        return array_column($data, 'name', 'id');
    }

    public function getSiblingRegionByName($name)
    {
        $data = $this->find()
            ->select(['pid'])
            ->where([
                'name' => $name,
                'status' => self::STATUS_VALID
            ])
            ->asArray()
            ->one();
        if (isset($data['pid'])) {
            return $this->getSiblingRegionByPid($data['pid']);
        }

        return [];
    }

    public function getSonRegionByName($name)
    {
        $data = $this->find()
            ->select(['id'])
            ->where([
                'name' => $name,
                'status' => self::STATUS_VALID
            ])
            ->asArray()
            ->one();
        if (isset($data['id'])) {
            return $this->getSiblingRegionByPid($data['id']);
        }

        return [];
    }
}