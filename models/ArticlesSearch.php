<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Articles;
use yii\helpers\ArrayHelper;

/**
 * ArticlesSearch represents the model behind the search form about `app\models\Articles`.
 */
class ArticlesSearch extends Articles
{
    public $created_at_range;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author_id'], 'integer'],
            [['title', 'text', 'desc', 'img', 'date', 'status'], 'safe'],
            [['created_at_range'], 'safe']
        ];
//        return ArrayHelper::merge(
//            [
//                [['created_at_range'], 'safe'] // add a rule to collect the values
//            ],
//            parent::rules()
//        );
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
        $query = Articles::find();

        $query->joinWith(['user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['user'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {

            list($start_date, $end_date) = explode(' - ', $this->created_at_range);
            $format_startdate = date("Y-m-d", strtotime($start_date));
            $format_end_date = date("Y-m-d", strtotime($end_date));
            $query->andFilterWhere(['between', 'date', $format_startdate, $format_end_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'author_id' => $this->author_id,
//            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'user.username', $this->author_id])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
