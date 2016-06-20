<?php

namespace common\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\user\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'gender', 'user_type', 'allow_post', 'grade', 'status'], 'integer'],
            [['mobile', 'password', 'nickname', 'avatar', 'bio', 'real_name', 'email', 'qq', 'wechat', 'id_no', 'opened_at', 'created_at', 'updated_at'], 'safe'],
            [['height', 'weight', 'appointment_fee'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'real_name', $this->real_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'nickname', $this->nickname]);

        return $dataProvider;
    }
}