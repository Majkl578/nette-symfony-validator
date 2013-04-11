# Symfony Validator component & Nette 2

Nette addon for integrating Symfony Validator component into Nette 2.


Requirements
------

- PHP 5.3.3 or newer
- Symfony Validator 2.2
- Doctrine Common 2.3
- Nette 2.0


Installation
------

1. Add "`majkl578/nette-symfony-validator`" to your dependencies in composer.json.
Don't forget to run `composer update`.
2. Register extension to start using this addon. Add the following call just before
the call `$configurator->createContainer()`:

```php
Majkl578\NetteSymfonyValidator\Config\Extension::register($configurator);
```

3. Delete cache.

You're done. ;)


Usage
------


Just use as normal service, e.g. autowire Symfony\Component\Validator\Validator.
For more general usage, see [Symfony 2 Validation documentation](http://symfony.com/doc/2.2/book/validation.html).


Issues
------

In case of any problems, just leave an issue here on GitHub (or, better, send a pull request).
