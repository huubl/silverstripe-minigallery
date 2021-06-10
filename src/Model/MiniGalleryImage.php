<?php


namespace Derralf\Minigallery;

use Page;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;


class MiniGalleryImage extends DataObject implements PermissionProvider
{
    private static $table_name = 'MiniGalleryImage';

    private static $singular_name = 'Minigalerie-Bild';
	private static $plural_name = 'Minigalerie-Bilder';
	private static $description = '';

	private static $db = [
        'Hidden' => 'Boolean',
        'Title'  => 'Varchar(255)',
        'Sort'   => 'Int'
    ];

	private static $has_one = [
	    'Image' => Image::class,
        'Page' => Page::class
    ];

	private static $has_many = [];

	private static $many_many = [];

	private static $belongs_many_many = [];

	private static $owns = [
        // do not "own" image, so we can unpublish images when $this is "hidden"
        // see below: onBeforeWrite
        // 'Image'
    ];

    private static $extensions = [
    ];

    private static $defaults = [];

	private static $default_sort = 'Sort ASC';

    private static $field_labels = [
        'Hidden'             => 'Deaktivieren',
        'Title'              => 'Titel',
        'Image'              => 'Bild',
        'Image.CMSThumbnail' => 'Bild',
        'Page'               => 'Seite',
        'Page.MenuTitle'     => 'Seite',
        'Sort'               => 'Sortierung'
    ];

    private static $summary_fields = [
        'Image.CMSThumbnail',
        'Title'
    ];

	private static $searchable_fields = [
        'Title'
    ];


    /**
     * get upload folder name
     * from Derralf\Minigallery\MiniGalleryPageExtension
     *
     * @return mixed
     */
    public function getMiniGalleryUploadFolderName()
    {
        if($this->Page()->exists()) {
            $page = $this->Page();
            return $page->getMiniGalleryUploadFolderName();
        }
        return Config::inst()->get('Derralf\Minigallery\MiniGalleryPageExtension', 'image_upload_foldername');
    }



	public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        // remove fields
        $fields->removeByName('Sort');

        // Title
        $fields->dataFieldByName('Title')->setDescription('nur für internen Gebrauch');

        // Image
        $Image = $fields->dataFieldByName('Image');
        $Image -> getValidator() -> setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
        $Image -> getValidator() -> setAllowedMaxFileSize(2 * 1024 * 1024); // 2MB
        $Image -> setDescription('Empfohlenes Format: lange Kante max. 1920 Pixel<br>Erlaubte Dateiformate: jpg, png, gif<br>Erlaubte Dateigröße: max. 2MB');
        $Image -> setFolderName($this->getMiniGalleryUploadFolderName());   // 'minigallery'
        $fields -> replaceField('Image', $Image);

        return $fields;
    }


    // publish or unpublish associated images
    // we don't want images to be accidentally accessible that are not supposed to be!
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if($this->Hidden) {
            $this->unpublishImage();
        } else {
            $this->publishImage();
        }
    }

    public function onBeforeDelete() {
        parent::onBeforeDelete();
        $this->unpublishImage();
    }

    public function publishImage() {
        if( $this->config()->get('auto_publish_image') && $this->Image()->exists() ) {
            $this->Image()->publishRecursive();
        }
    }

    public function unpublishImage() {
        if( $this->config()->get('auto_unpublish_image') && $this->Image()->exists() ) {
            $this->Image()->doUnpublish();
        }
    }




	/* PERMISSIONS */

	public function providePermissions() {
	    $objectName = property_exists($this, 'singular_name') ? self::$singular_name : $this->ClassName;
		$categoryName = 'Eigene Berechtigungen: ' . $objectName . " bearbeiten";
		return array(
            'MiniGalleryImage_VIEW' => array (
                'name'		=>	$objectName . ' betrachten (view)',
                'category'	=>	$categoryName
            ),
            'MiniGalleryImage_EDIT' => array (
                'name'		=>	$objectName . ' bearbeiten (edit)',
                'category'	=>	$categoryName
            ),
            'MiniGalleryImage_DELETE' => array (
                'name'		=>	$objectName . ' löschen (delete)',
                'category'	=>	$categoryName
            ),
            'MiniGalleryImage_CREATE' => array (
                'name'		=>	$objectName . ' erstellen (create)',
                'category'	=>	$categoryName
            ),
            'MiniGalleryImage_PUBLISH' => array (
                'name'		=>	$objectName . ' veröffentlichen (publish)',
                'category'	=>	$categoryName
            )
        );
	}

	public function canView($member = null){
        return Permission::check('MiniGalleryImage_VIEW');
    }
	public function canEdit($member = null) {
        return Permission::check('MiniGalleryImage_EDIT');
    }
	public function canDelete($member = null) {
        return Permission::check('MiniGalleryImage_DELETE');
    }
	public function canCreate($member = null, $context = array()){
        return Permission::check('MiniGalleryImage_CREATE');
    }
	public function canPublish($member = null){
        return Permission::check('MiniGalleryImage_PUBLISH');
    }

}
