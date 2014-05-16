# I18n Search Plugin for Croogo #

Version 2.0 for croogo 2.0 or higher.

This plugin allows you to search through node-translations too and is supposed
to be used together with the Translate plugin from croogo.

## Installation ##

Clone/Upload the content of this repository to /app/Plugin/I18nSearch, and
activate the plugin from your admin panel.

## Sample of usage ##

On your view just add a search form that uses the I18nSearch plugin. The
following example shows how to customize the search form for using it with
Bootstrap as inline in a navbar:

```php
<?php echo $this->Form->create('I18nSearch.Search', array(
        'class' => 'form-inline',
        'admin' => false, 
        'type' => 'get',
        'url' => array(
            'locale' => $this->params['locale'],
            'plugin' => 'i18n_search', 
            'controller' => 'search', 
            'action' => 'show')));
    $this->Form->unlockField('q');
    echo $this->Form->input('q', array(
        'format' => array(
            'before', 'label', 'between',
            'input', 'error', 'after'),
        'label' => false,
        'between' => '<div class="controls">',
        'div' => false,
        'after' => '<button ' .
            'class="btn btn-inverse btn-mini" ' .
            'type="submit"> <i ' . 
            'class="icon-search ' .
            'icon-white"></i></button></div>',));
    echo $this->Form->end(); 
?>
```

