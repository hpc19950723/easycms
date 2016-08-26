<?php

namespace common\modules\admin\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\modules\admin\models\AdminMenu;

class AdminMenuSearch extends Model
{

    const TYPE_ROUTE = 101;

    public $name;
    public $route;
    public $env;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'route', 'env'], 'safe'],
            [['env'], 'integer'],
        ];
    }

    /**
     * Search authitem
     * @param array $params
     * @return \yii\data\ActiveDataProvider|\yii\data\ArrayDataProvider
     */
    public function search($params)
    {
        $this->load($params);
        
        $query = AdminMenu::find()->where(['parent_id' => 0])
                ->orderBy(['position' => SORT_ASC]);
        
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'route', $this->route]);
        $query->andFilterWhere(['env' => $this->env]);
        $models = $query->all();
        
        $sortedModels = [];
        foreach($models as $model) {
            $sortedModels[] = $model;
            $childMenuQuery = AdminMenu::find()->where(['parent_id' => $model->menu_id])
                ->orderBy(['position' => SORT_ASC]);
            
            $childMenuQuery->andFilterWhere(['like', 'name', $this->name]);
            $childMenuQuery->andFilterWhere(['like', 'route', $this->route]);
            $childMenuQuery->andFilterWhere(['env' => $this->env]);
            $childMenuModels = $childMenuQuery->all();
            
            if(count($childMenuModels)) {
                $sortedModels = array_merge($sortedModels, $childMenuModels);
            }
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $sortedModels,
            'key' => 'menu_id',
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        
        return $dataProvider;
    }

}
