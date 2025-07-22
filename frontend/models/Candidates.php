<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class Candidates extends ActiveRecord
{
    /**
     * Jina la table ya database inahusiana na model hii.
     */
    public static function tableName()
    {
        return 'candidates';  // Hii ni jina la table kwenye database yako
    }

    /**
     * Hapa ni mahali ambapo tunaweza kuweka rules za validation za model hii.
     */
    public function rules()
    {
        return [
            [['name', 'photo'], 'required'],  // Uwanja wa jina na picha ni lazima
            [['votes'], 'integer'],           // Kura lazima iwe nambari
            [['name'], 'string', 'max' => 100],  // Jina linaweza kuwa na urefu wa mpaka 100 characters
            [['photo'], 'string', 'max' => 255], // Picha inaweza kuwa na urefu wa mpaka 255 characters
            [['votes'], 'default', 'value' => 0],  // Ikiwa kura hazijawekwa, weka 0 kama default
        ];
    }

    /**
     * Labels za attributes ili kusaidia katika tafsiri ya model hii.
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Candidate Name', // Jina la mgombea
            'photo' => 'Candidate Photo', // Picha ya mgombea
            'votes' => 'Votes',  // Idadi ya kura zilizopigwa kwa mgombea huyu
        ];
    }

    /**
     * Hii ni function ya kuongeza kura kwa mgombea.
     * Tunatumia hii wakati kura zinapopigwa.
     */
    public function increaseVoteCount()
    {
        $this->votes += 1; // Ongeza moja kwenye kura zilizopo
        return $this->save();  // Hifadhi mabadiliko kwenye database
    }

    /**
     * Hii ni function ya kupata picha ya mgombea kutoka kwa database.
     * Inaruhusu picha za mgombea kupatikana kwa urahisi kwenye UI.
     */
    public function getCandidatePhoto()
    {
        return Yii::getAlias('@web/images/' . $this->photo);  // Path ya picha kwenye server
    }
}
