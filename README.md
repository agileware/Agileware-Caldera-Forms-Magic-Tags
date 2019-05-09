# Agileware Caldera Forms Magic Tags
A Caldera Forms magic tags collection that used by Agileware.

## Description
A Caldera Forms magic tags collection that used by Agileware. Most magic tags here work with CiviCRM, so make sure to enable CiviCRM before using this plugin.

### Magic Tags So Far
From version 1.1.0, we got:
- contact:related-subtype
- contact:subtype
- member:membership
- member:membership_type
- member:membership_value
- member:renewal
- user:roles

## Developer
Here is some helpful information for developers.

### How to add new magic tag
For example, if you want to add a magic tag {example:your_tag}

1. Under tags directory, create a file called `example.your_tag.php`
1. Inside this file, create a function called `agileware_magic_tags_callback_example_your_tag`. Then the function looks like:  
```php
function agileware_magic_tags_callback_example_your_tag($value) {
    return $value;
}
```

Then the framework will know your magic tag by the file name and will callback the function when your tag is fired.  
Note: The function name **should** match the pattern `agileware_magic_tags_callback_[whatever_your_magic_tag_name_is]`.

### Helper function
The are two files contain helper functions, `master_helper_functions.php` and `custom_helper_functions.php`, and will be load by the framework.  
I recommend developer to put the helper function (all functions other than the callback function) to `custom_helper_functions.php` if you don't want to look for existing helper functions.  

## Upgrade Notice

### 1.1
* New framework to manage magic tags.
* all tags go to separated file

## Changelog

### 1.1
* New framework to manage magic tags.
* all tags go to separated file

### 1.0
Testing version with all magic tags registered in one file.