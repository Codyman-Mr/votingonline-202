<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Candidate extends ActiveRecord
{
    public $imageFile; // variable ya kupokea uploaded file

    public static function tableName()
    {
        return 'candidates';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['photo'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'jpg, jpeg, png', 'skipOnEmpty' => true], // validation ya file
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Candidate Name',
            'photo' => 'Candidate Photo',
            'imageFile' => 'Upload Photo',
        ];
    }

    // Method ya upload, inaweza kuitwa controller
    public function uploadPhoto()
    {
        if ($this->validate()) {
            $file = UploadedFile::getInstance($this, 'imageFile');
            if ($file) {
                $fileName = time() . '.' . $file->extension;
                $filePath = Yii::getAlias('@frontend/web/uploads/') . $fileName;
                if ($file->saveAs($filePath)) {
                    $this->photo = $fileName; // hifadhi jina tu, sio path nzima
                    return true;
                }
            }
        }
        return false;
    }
}
