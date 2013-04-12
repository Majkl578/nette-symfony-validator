# Symfony Validator component & Nette 2

Nette addon for integrating Symfony Validator component into Nette 2.


Requirements
------

- PHP 5.3.3 or newer
- Symfony Validator 2.2
- Doctrine Common 2.3
- Nette 2.0
- Doctrine ORM 2.3 (optional)


Installation
------

* Add "`majkl578/nette-symfony-validator`" to your dependencies in composer.json.
Don't forget to run `composer update`.
* Register extension to start using this addon. Add the following call just before
the call `$configurator->createContainer()`:

```php
Majkl578\NetteSymfonyValidator\Config\Extension::register($configurator);
```

* If you want to use integration with Doctrine ORM, register also ORM extension:

```php
Majkl578\NetteSymfonyValidator\Config\ORMExtension::register($configurator);
```

* Delete cache.

You're done. ;)


General usage
------

Just use as normal service, e.g. autowire Symfony\Component\Validator\Validator.
For more general usage, see [Symfony 2 Validation documentation](http://symfony.com/doc/2.2/book/validation.html).


Doctrine 2 ORM
------

Validation is performed automatically only on new or changed entities.
The validation is registered automatically through event listener.
It is listening for preFlush event so the validation itself is performed after
calling EntityManager::flush(), just before committing changes to database.

For more information about adding constraints to fields, see
[Properties in Symfony 2 Validation documentation](http://symfony.com/doc/current/book/validation.html#properties).
Here's just a simple example:
```php
namespace Example;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class User
{
	/**
	 * @ORM\Column
	 * @Assert\NotBlank
	 */
	private $name;

	/**
	 * @ORM\Column
	 * @Assert\NotBlank
	 * @Assert\Email
	 */
	private $email;

	// ...
}

```


Issues
------

In case of any problems, just leave an issue here on GitHub (or, better, send a pull request).
