<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "voting_records".
 *
 * @property int $id
 * @property string $voter_id_number
 * @property string $full_name
 * @property string|null $voted_at
 * @property string|null $candidate_name  // Ongeza hii property
 */
class VotingRecords extends \yii\db\ActiveRecord
{
    public $candidate_name; // Hii ni property ili ihifadhi jina la mgombea

    public static function tableName()
    {
        return 'voting_records';
    }

    public function rules()
    {
        return [
            [['voter_id_number', 'full_name', 'candidate_name'], 'required'], // Ongeza candidate_name kama required
            [['voted_at'], 'safe'],
            [['voter_id_number'], 'string', 'max' => 100],
            [['full_name'], 'string', 'max' => 255],
            [['candidate_name'], 'string', 'max' => 255],  // Validation ya jina la mgombea
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voter_id_number' => 'Voter ID Number',
            'full_name' => 'Full Name',
            'voted_at' => 'Voted At',
            'candidate_name' => 'Candidate Name',  // Label kwa candidate_name
        ];
    }
}


