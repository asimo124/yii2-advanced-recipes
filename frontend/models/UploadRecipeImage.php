<?php
namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadRecipeImage extends Model
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

    public function upload($recipeId)
    {
        $retVal = $this->validate();

        if (!$retVal || !$this->imageFile) {
            return '';
        } else {
            $this->imageFile->saveAs('uploads/recipe_pics/' . $recipeId . '.' . $this->imageFile->extension);
            return 'uploads/recipe_pics/' . $recipeId . '.' . $this->imageFile->extension;
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