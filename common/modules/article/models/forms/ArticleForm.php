<?php

namespace common\modules\article\models\forms;

use yii\base\Model;
use yii\web\NotFoundHttpException;
use common\modules\article\models\Article;
use common\modules\core\components\ImageUploader;
use common\modules\core\components\Tools;

class ArticleForm extends Model
{
    public $title;

    public $image;

    public $content;

    public $status = Article::STATUS_ACTIVE;
    
    public $category_id;
    
    public $link;

    private $_article;

    //定义场景
    const SCENARIOS_CREATE = 'create';
    const SCENARIOS_UPDATE = 'update';

    public function rules() {
        return [
            [['title', 'status', 'category_id'], 'required'],
            [['content', 'link'], 'required', 'when' => function() {
                if($this->content || $this->link) {
                    return false;
                } else {
                    return true;
                }
            }, 'whenClient' => "function (attribute, value) {
                if ($('#articleform-link').val() != '' || $('#articleform-content').val() != '') {
                    return false;
                } else {
                    return true;
                }
            }", 'message' => '文章内容与外部链接必须二选其一'],
            [['title'], 'string', 'max' => 50, 'min' => 5],
            [['link'], 'string', 'max' => 255],
            ['identifier','match', 'pattern'=>'/^[a-z0-9_]*?$/','message' => '输入格式不正确,只能包含小写字母,数值,下划线(_). 如, "example_page".'],
            ['link', 'match', 'pattern'=>'/^((http|ftp|https):\/\/)+[\w\-_\.]+[\/\w\-_]+[\w\-_\.\?&~\+;:@#=\$\^,]*$/','message' => 'URL的格式不正确'],
            ['image', 'image', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png', 'checkExtensionByMimeType' => false],
        ];
    }

    public function scenarios() {
        $scenarios = [
            self:: SCENARIOS_CREATE => ['title', 'content', 'image', 'category_id', 'status', 'link'],
            self:: SCENARIOS_UPDATE => ['title', 'content', 'image', 'category_id', 'status', 'link'],
        ];
        return array_merge( parent:: scenarios(), $scenarios);
    }

    
    public function attributeLabels()
    {
        return [
            'title'         => '文章标题',
            'image'         => '缩略图',
            'content'       => '文章内容',
            'category_id'   => '文章分类',
            'status'        => '文章状态',
            'link'  => '外部链接'
        ];
    }
    
    
    public function getIsNewRecord()
    {
        return $this->_article === null;
    }
    
    
    public function initData($id)
    {
        $this->_article = Article::findOne($id);
        if ($this->_article === null) {
            throw new NotFoundHttpException('页面不存在');
        }
        
        $this->attributes = $this->_article->attributes;
    }
    
    
    /**
     * 保存资讯
     * @return boolean
     */
    public function save()
    {
        if($this->validate()) {
            if($this->getIsNewRecord()) {
                $this->_article = new Article();
            }
            
            $fileUploader = new ImageUploader([
                'uploadedFile' => $this->image,
                'uploadedFileDir' => Tools::getUploadDir('article'),
                'uploadedFilePrefix' => 'ATL',
                'oldFilePath' => $this->_article->image?:null
            ]);
            $imageName = $fileUploader->save();
            if($imageName) {
                $this->_article->image = Tools::getUploadDir('article') . '/' . $imageName;
            }
            $this->_article->title = $this->title;
            $this->_article->content = $this->content;
            $this->_article->category_id=$this->category_id;
            $this->_article->status = $this->status;
            $this->_article->link = $this->link;

            return $this->_article->save();
        } else {
            return false;
        }
    }
}