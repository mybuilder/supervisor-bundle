# Supervisor Bundle

[![Build Status](https://travis-ci.org/mybuilder/supervisor-bundle.svg?branch=master)](https://travis-ci.org/mybuilder/supervisor-bundle)

A bundle for Symfony 4/5 which allows you to use `@Supervisor` annotations to configure how [Supervisor](http://supervisord.org/) runs your console commands.

## Installation

### Install with composer

Run the composer require command:

``` bash
$ php composer.phar require mybuilder/supervisor-bundle
```

### Enable the bundle

Enable the bundle in the `config/bundles.php` for Symfony:

```php
return [
    MyBuilder\Bundle\SupervisorBundle\MyBuilderSupervisorBundle::class => ['all' => true],
];
```

### Configure the bundle

You can add the following to `packages/my_builder_supervisor.yaml` for Symfony to define your global export configuration:

```yaml
my_builder_supervisor:
    exporter:
        # any Supervisor program options can be specified within this block
        program:
            autostart: 'true'
        
        # allows you to specify a program that all commands should be passed to
        executor: php 
        
        # allows you to specify the console that all commands should be passed to
        console: bin/console
```

## Usage

The first step is to add the `use` case for the annotation to the top of the command you want to use the `@Supervisor` annotations in.

```php
use MyBuilder\Bundle\SupervisorBundle\Annotation\Supervisor;
```

Then define the `@Supervisor` annotation within the command's PHPDoc, which tells Supervisor how to configure this program.
The example below declares that three instances of this command should be running at all times on the server entitled 'web', with the provided parameter `--send`.

```php
/**
 * Command for sending our email messages from the database.
 *
 * @Supervisor(processes=3, params="--send", server="web")
 */
class SendQueuedEmailsCommand extends Command {}
```


## Exporting the Supervisor configuration

You should run `bin/console supervisor:dump` and review what the Supervisor configuration will look like based on the current specified definition.
If you are happy with this you can write out the configuration to a `conf` file:

```
$ bin/console supervisor:dump --user=mybuilder --server=web > "/etc/supervisor.d/symfony.conf"
```

And then reload Supervisor:

```
$ kill -SIGHUP $(supervisorctl pid)
```

### Environment

You can choose which environment you want to run the commands in Supervisor under like this:

```
$ bin/console supervisor:dump --server=web --env=prod
```

---

Created by [MyBuilder](http://www.mybuilder.com/) - Check out our [blog](http://tech.mybuilder.com/) for more insight into this and other open-source projects we release.
