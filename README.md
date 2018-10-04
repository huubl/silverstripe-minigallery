# SilverStripe Minigallery

Simple experimental Module to add a small Gallery to any paty type

Private project, no help/support provided

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