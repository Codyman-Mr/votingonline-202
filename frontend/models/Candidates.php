<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Candidates extends ActiveRecord
{
    public $photoFile; // property ya kupokea uploaded file

    public static function tableName()
    {
        return 'candidates';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['votes'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['photo'], 'string', 'max' => 255],
            [['photoFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
            [['votes'], 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Candidate Name',
            'photo' => 'Candidate Photo',
            'photoFile' => 'Upload Photo',
            'votes' => 'Votes',
        ];
    }

    public function uploadPhoto()
    {
        if ($this->validate() && $this->photoFile) {
            $uploadPath = Yii::getAlias('@frontend/web/uploads/');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileName = time() . '_' . uniqid() . '.' . $this->photoFile->extension;
            $fullPath = $uploadPath . $fileName;

            if ($this->photoFile->saveAs($fullPath)) {
                $this->photo = $fileName;
                return true;
            }
        }
        return false;
    }

    public function getCandidatePhoto()
    {
        return Yii::getAlias('@web/uploads/' . $this->photo);
    }
}
