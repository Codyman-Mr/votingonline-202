<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Candidate extends ActiveRecord
{
    /**
     * @var UploadedFile|null file upload
     */
    public $photoFile; // tutaweka property mpya ku hold file upload (optional)

    public static function tableName()
    {
        return 'candidates';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],

            // photo ni string kwa DB, lakini wakati wa upload tuna validate kama file
            [['photo'], 'string', 'max' => 255],

            // Hii ni validation ya upload, ikitokea photoFile si empty
            [['photoFile'], 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png', 'skipOnEmpty' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Candidate Name',
            'photo' => 'Candidate Photo',
            'photoFile' => 'Upload Photo',
        ];
    }

    /**
     * Upload photo method
     */
    public function uploadPhoto()
    {
        // Tumia photoFile property ambayo ni UploadedFile
        if ($this->validate() && $this->photoFile) {
            $uploadPath = 'uploads/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileName = time() . '.' . $this->photoFile->extension;
            $fullPath = $uploadPath . $fileName;

            if ($this->photoFile->saveAs($fullPath)) {
                $this->photo = $fileName; // Hifadhi jina la file kwenye DB
                return true;
            }
        }
        return false;
    }
}
