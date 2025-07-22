class VotingRecords extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'voting_records';
    }

    public function rules()
    {
        return [
            [['voter_id', 'candidate_id', 'candidate_name'], 'required'],
            [['voter_id', 'candidate_id'], 'integer'],
            [['voted_at'], 'safe'],
            [['candidate_name'], 'string', 'max' => 255],
            [['voter_id_number'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voter_id' => 'Voter ID',
            'candidate_id' => 'Candidate ID',
            'voted_at' => 'Voted At',
            'candidate_name' => 'Candidate Name',
            'voter_id_number' => 'Voter ID Number',
        ];
    }

    // Relation to Voter model
    public function getVoter()
    {
        return $this->hasOne(Voter::class, ['id' => 'voter_id']);
    }

    // Relation to Candidate model
    public function getCandidate()
    {
        return $this->hasOne(Candidate::class, ['id' => 'candidate_id']);
    }
}
