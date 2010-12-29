# BeepBeep

BeepBeep is a small library for quickly creating web applications. It is modelled after popular frameworks like Sinatra and Camping. It supports a small router and uses PHP itself for templating.

# Requirements

BeepBeep is build for PHP 5.3 and up. It makes use of some of PHP 5.3's features like closures and late static binding. BeepBeep also uses output buffering for view rendering.

# Example

Use a DSL style notation:

    :::php
    require 'beep/beep.php';
    
    beep::get('/', function() {
        echo 'Hello World';
    });
    
    beep::beep();

Or you can use the Application class:
    
    :::php
    $app = new \beep\Application();
    
    $app->get('/', function() {
        echo 'Hello World';
    });
    
    $app->dispatch();

# Status

BeepBeep is still alpha software and in development. Things will change and any help is welcome :)

# License

BeepBeep is license under the MIT license. See the LICENSE file for the full license text.