# Fountain Parser

Fountain is a simple markup syntax that allows screenplays to be written, edited, and shared in plain, human-readable text. Fountain allows you to work on your screenplay anywhere, on any computer, using any software that edits text files.

For more details on Fountain see http://fountain.io.

A demo is available on [heroku](https://fountain-livewire.herokuapp.com) for testing.

## Getting started

The simple version for parsing a screenplay text straight into HTML:

```php
    $input = "My fountain input text.";
    $screenplay = new \Fountain\Screenplay();
    $html = $screenplay->parse($input);
```

The longer version is that Fountain first creates a collection of Elements, which you may use for other purposes.
Once the Fountain Elements have been parsed, the FountainTags class determines the correct HTML tags to print. 

```php
    $input = "My fountain input text.";
    // determine fountain elements
    $fountainElements = (new \Fountain\FountainParser())->parse($input);
    // parse fountain elements into html
    $html = (new \Fountain\FountainTags())->parse($fountainElements);
```

## Mentions
The code has been built upon the previous work of these contributors.

 * [Alex King (PHP port)](https://github.com/alexking/Fountain-PHP)
 * [Yousefi & John August (original Objective-C version)](https://github.com/nyousefi/Fountain)
