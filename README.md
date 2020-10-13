# Fountain Parser

A PHP markdown parser for [fountain](https://fountain.io). 

The parser currently uses some CommonMark elements for inline markdown processing.

## Getting started

```php
    $input = "My fountain input text.";
    // determine fountain elements
    $fountainElements = (new FountainParser())->parse($input);
    // parse fountain elements into html
    $html = (new FountainTags())->parse($fountainElements);
```
