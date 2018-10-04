<?php

namespace Derralf\Minigallery;

use Colymba\BulkUpload\BulkUploader;
use Derralf\GridFieldToggleVisibility\GridFieldToggleHiddenAction;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\ORM\DataExtension;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;


class MiniGalleryPageExtension extends DataExtension
{

    use Configurable;

    private static $db = [
        'MiniGalleryCols'	=> "Enum('4,3,2,1','4')"
    ];

    private static $has_many = [
        'MiniGalleryImages'	=>	MiniGalleryImage::class
    ];

    private static $owns = [
        'MiniGalleryImages'
    ];

    public function updateCMSFields(FieldList $fields) {





        if($this->isMiniGalleryllowedPageType()) {

            // Columns
            $MiniGalleryCols = new DropdownField('MiniGalleryCols', "Anzahl Spalten", $this->TranslatedMiniGalleryColsEnumValues());
            $fields->addFieldToTab('Root.Minigalerie', $MiniGalleryCols);


            // MiniGalleryImages: GridField
            $MiniGalleryImagesConfig = GridFieldConfig_RelationEditor::create(50);
            $MiniGalleryImagesConfig->removeComponentsByType('GridFieldDeleteAction');
            $MiniGalleryImagesConfig->addComponent(new GridFieldOrderableRows('Sort'));
            $MiniGalleryImagesConfig->addComponent(new GridFieldToggleHiddenAction('Hidden'));
            $MiniGalleryImagesConfig->addComponent(new BulkUploader());

            $MiniGalleryImagesConfig->getComponentByType('Colymba\\BulkUpload\\BulkUploader')
                ->setUfSetup('setFolderName','minigallery')
                ->setUfSetup('setAllowedExtensions', array('jpg', 'jpeg', 'gif', 'png'))
                ->setAutoPublishDataObject(true);
                //->setUfSetup('setAllowedMaxFileSize', (2 * 1024 * 1024)); // 2MB
                //->setUfValidatorSetup('setAllowedMaxFileSize', (2 * 1024 * 1024)); // 2MB
                //->getValidator()->setAllowedMaxFileSize(2 * 1024 * 1024); // 2MB
                // ->setUfConfig('sequentialUploads', true);



            $MiniGalleryImagesField = new GridField(
                'MiniGalleryImages', // Field name
                'Minigalerie-Bilder', // Field title
                $this->owner->MiniGalleryImages(), // List of all related Elements
                $MiniGalleryImagesConfig
            );
            $fields->addFieldToTab('Root.Minigalerie', $MiniGalleryImagesField);
        }

		return $fields;
	}


    /**
     * check if current Page is allowed to display Minigallery
     * @return bool
     */
    public function isMiniGalleryllowedPageType() {

        $is_allowed = true;

        $allowed_pagetypes = $this->config()->get('allowed_pagetypes');
        $disallowed_pagetypes = $this->config()->get('disallowed_pagetypes');

        // if we have a "positive list"
        if(is_array($allowed_pagetypes)) {
            if (in_array(get_class($this->owner), $allowed_pagetypes)) {
                $is_allowed = true;
            } else {
                $is_allowed = false;
            }
            return $is_allowed;
        }

        // if we have a "negative list"
        if(is_array($disallowed_pagetypes)) {
            if (in_array(get_class($this->owner), $disallowed_pagetypes)) {
                $is_allowed = false;
            } else {
                $is_allowed = true;
            }
            return $is_allowed;
        }

        // let's assume we have no negative/positive list: return true
        return true;
    }


    /**
     * return active/valid images
     * @return false|mixed
     */
    public function ActiveMiniGalleryImages() {

        if(!$this->isMiniGalleryllowedPageType()) {
            return false;
        }
        return $this->owner->MiniGalleryImages()
            ->filter(array('Hidden' => 0))
            ->filterByCallback(function($item) {
                if ($item->Image()->exists()) {return true;}
            });
    }


    public function TranslatedMiniGalleryColsEnumValues($namespace = 'Derralf\Minigallery\MiniGalleryCols') {

        $options = $this->owner->dbObject('MiniGalleryCols')->enumValues();

        if (!empty($options)) {
            foreach ($options as $value) {
                $translatedOptions[$value] = _t("$namespace.$value", $value);
            }
        }
        return $translatedOptions;
    }

}


