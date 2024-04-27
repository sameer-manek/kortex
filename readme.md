# PHP Kortex

A PHP framework which is
- Simple to use.
- Fast.
- Asynchronous and built on top of Swoole.
- Easy to extend and scale.

I have been a long time Laravel user and while I have always been in awe of it, I regularly feel it is slow. And it is bound to happen with all the features that laravel comes with, and very few other frameworks even come close while being more performant. Hence I thought of building a framework myself, this could be a fun learning experience and perhaps can help someone else.

### Installation

**Dependencies**
- PHP 8.x
- Swoole
- MySQL (Database Connection are WIP)

once you have the project, just clone this repo, rename it as you will, use `data/config.sample.php` create a new config.php file in same dir. Also create a `data/logs` dir.

once done, run `php index.php` to start the framework and off you go.

*NOTE* To host your application, SSH access to the server will be necessary.