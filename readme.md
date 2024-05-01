# Kortex

A PHP framework which is
- Simple to use.
- Fast.
- Asynchronous and built on top of Swoole.
- Easy to extend and scale.

I have been a long time Laravel user and while I have always been in awe of it, I regularly feel it is slow. And it is bound to happen with all the features that laravel comes with, and very few other frameworks even come close to being as feature-rich as Laravel while being more performant. But, I need performance for simple web platforms; Hence I thought of building a framework myself, this could be a fun learning experience and perhaps can also help someone else.

### Installation

**Dependencies**

*Local*
- PHP 8.x
- Swoole
- MySQL
- [optional] Enable Zend OPcache

*External*
- Twig (v3.x) - for rendering views.

once you have the project, just clone this repo, rename it as you will, use `data/config.sample.php` create a new `config.php` file in same dir. Run `composer install`in the root directory and also create a `data/logs` directory to store logs.

once done, run `php index.php` to start the framework and off you go.

*NOTE* To host your application, SSH access to the server will be necessary.

### Todo

- [X] Basic HTTP request handling
- [X] Extend Swoole\Http\Response
- [X] Extend Swoole\Http\Request
- [X] Basic Controllers
- [X] Basic Routing
- [X] Middleware support
- [ ] Advanced routing with in-route params
- [X] Add twig templating engine
- [X] DB Connection (MySQL. Needs to be optimized)
- [X] Encryption & Hashing utils
- [ ] HTTP utils (cookie managers, session managers, etc)
- [ ] Socket utils
- [ ] General utils (random number/string generator, and others)
- [X] Basic logging
- [ ] Testing
- [ ] Exceptions
- [ ] Caching (actively looking for redis alternatives. boooo redis)
- [X] Global Configuration
- [ ] Task/Job queue
- [ ] SMTP Util
- [ ] Documentation