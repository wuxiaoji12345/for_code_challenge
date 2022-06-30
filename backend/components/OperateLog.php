<?php
namespace backend\components;
use Yii;
use yii\db\ActiveRecord;

class OperateLog{
    public static function write($event)
    {
        //判断是否登录 如果没有登录 直接略过
        if (Yii::$app->user->isGuest) {
            return "";
        }
        $enableLogTable   =   Yii::$app->params['enableLogTable'];
        $tableName   =   $event->sender->tableSchema->name;
        if(!in_array($tableName, $enableLogTable))  return "";
        if ($event->name == ActiveRecord::EVENT_AFTER_INSERT) {
            $description = "%s新增了表%s %s:%s的%s";
            $type   =   'create';
        } elseif($event->name == ActiveRecord::EVENT_AFTER_UPDATE) {
            $description = "%s修改了表%s %s:%s的%s";
            $type   =   'update';
        } else {
            $description = "%s删除了表%s %s:%s%s";
            $type   =   'delete';
        }
        if (!empty($event->changedAttributes)) {
            $desc = '';
            foreach($event->changedAttributes as $name => $value) {
                $desc .= $event->sender->getAttributeLabel($name) . ' : ' . $value . '=>' . $event->sender->getAttribute($name) . ',';
            }
            $desc = substr($desc, 0, -1);
        } else {
            $desc = '';
        }
        $userName = Yii::$app->user->identity->nickname;
        $description = sprintf($description, $userName, $tableName, $event->sender->primaryKey()[0], $event->sender->getPrimaryKey(), $desc);
        $userId = Yii::$app->user->id;
        $ip = Yii::$app->request->userIP;
        $data = [
            'primaryid'=>$event->sender->getPrimaryKey(),
            'tablename'=>$tableName,
            'description' => $description,
            'urid' => $userId,
            'ip' => $ip,
            'type'=>$type
        ];
        $model = new \backend\models\Operatelog();
        $model->setAttributes($data);
        $model->save();
    }
}