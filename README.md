# Fountain Parser

A PHP markdown parser for [fountain](https://fountain.io).

A demo is available on [heroku](https://fountain-livewire.herokuapp.com) for testing.

## Getting started

```php
    $input = "My fountain input text.";
    // determine fountain elements
    $fountainElements = (new FountainParser())->parse($input);
    // parse fountain elements into html
    $html = (new FountainTags())->parse($fountainElements);
```

## Mentions
The code has been built upon the previous work of these contributors.

 * [Alex King (PHP port)](https://github.com/alexking/Fountain-PHP)
 * [Yousefi & John August (original Objective-C version)](https://github.com/nyousefi/Fountain)
