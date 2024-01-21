<?php
namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadShowImage extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload($showId)
    {
        $retVal = $this->validate();

        if (!$retVal || !$this->imageFile) {
            return '';
        } else {
            $this->imageFile->saveAs('uploads/show_pics/' . $showId . '.' . $this->imageFile->extension);
            return 'uploads/show_pics/' . $showId . '.' . $this->imageFile->extension;
        }
    }

    public function formatFileName($fileName)
    {
        $formattedName = $fileName;
        $formattedName = str_replace(" ", "_", $formattedName);
        $formattedName = str_replace("-", "_", $formattedName);
        return $formattedName;
    }
}

?>