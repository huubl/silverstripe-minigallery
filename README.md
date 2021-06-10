# SilverStripe Minigallery

Simple experimental Module to add a small Gallery to any paty type

Private project, no help/support provided

Objects ab not versioned but can be "hidden".  
Attached images will get published/unpublished depending on whether on the object itself "hidden" is activated or not.  
See below how to disable this behavior.

## Requirements

* SilverStripe ^4.1
* silverstripe/vendor-plugin
* symbiote/silverstripe-gridfieldextensions
* derralf/silverstripe-gridfieldtogglevisibility
* jonom/focuspoint ^3.0


## Installation

- Install the module via Composer
  ```
  composer require derralf/silverstripe-minigallery
  ```

## Templates

```
<% include Derralf\\Minigallery\\Minigallery %>
```

## Configuration

In your config.yml:


Config example 1 (extend single Page Types)


```
SilverStripe\Blog\Model\BlogPost:
  extensions:
    - Derralf\Minigallery\MiniGalleryPageExtension
```

Config example 2 (define include list)


```
Page:
  extensions:
    - Derralf\Minigallery\MiniGalleryPageExtension

Derralf\Minigallery\MiniGalleryPageExtension:
  allowed_pagetypes:
    - SilverStripe\Blog\Model\BlogPost
    - My\Namespace\SomePageType
    - Other\Namespace\OtherPageType
  
```

Config example 3 (define exclude list)

```
Page:
  extensions:
    - Derralf\Minigallery\MiniGalleryPageExtension

Derralf\Minigallery\MiniGalleryPageExtension:
  disallowed_pagetypes:
    - My\NameSpace\HomePage
    - My\Other\Excluded\PageType
  
```

**Don't** mix `allowed_pagetypes` and `disallowed_pagetypes`


### Set image upload folder name

1. custom function on your page type

```
## e.g.
public function getCustomMiniGalleryUploadFolderName() {
    return 'minigallery-partner-' . $this->URLSegment;
}


if(method_exists($this->owner, 'getCustomMiniGalleryUploadFolderName')) {
            return $this->owner->getCustomMiniGalleryUploadFolderName();
        }
        if($this->owner->config()->get('minigallery_upload_foldername')) {
            return $this->owner->config()->get('minigallery_upload_foldername');
        }
        return $this->config()->get('image_upload_foldername');
```

or 2. per page type config

```
Page:
  minigallery_upload_foldername: 'minigallery-page'

HomePage:
  minigallery_upload_foldername: 'minigallery-homepage'

```

or 3. with extension config (defaults to 'minigallery')

```
Derralf\Minigallery\MiniGalleryPageExtension:
  image_upload_foldername: 'minigallery'

```

### Disable Auto-Publish and Auto-Unpublish

The associated Image is automatically published or unbulblished on save depending on whether "hidden" is activated or not

You can disable this behaviour by adding this to your config:

```
---
name: MyMinigallery
after: Minigallery
---

Derralf\Minigallery\MiniGalleryImage:
  auto_publish_image: false
  auto_unpublish_image: false
```

