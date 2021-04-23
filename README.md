# Oni-Bus
[![Build](https://github.com/brenoroosevelt/oni-bus/actions/workflows/ci.yml/badge.svg)](https://github.com/brenoroosevelt/oni-bus/actions/workflows/ci.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/brenoroosevelt/oni-bus/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/brenoroosevelt/oni-bus/?branch=main)
[![codecov](https://codecov.io/gh/brenoroosevelt/oni-bus/branch/main/graph/badge.svg?token=S1QBA18IBX)](https://codecov.io/gh/brenoroosevelt/oni-bus)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)

Message Bus, Command Bus, Query Bus and Event Bus.

## Features

- Command Bus
- Query Bus
- Event Bus
- Map Handlers using PHP 8 Attributes

## Requirements

The following versions of PHP are supported: `7.1`, `7.2`, `7.3`, `7.4`, `8.0`.

## Install

``` bash
$ composer require brenoroosevelt/oni-bus
```

## Usage

### Command Bus

```php
<?php
use OniBus\Command\Command;   

class UserRegistrationCommand implements Command
{
    public $username;
    public $password;
}
```

```php
<?php
use OniBus\Attributes\CommandHandler;

class UserRegistrationHandler
{
    #[CommandHandler]
    public function register(UserRegistrationCommand $userRegistration)
    {
        /* ... */
        return new UserDTO(/* ... */);
    }
}
```

```php
<?php
use OniBus\Attributes\CommandHandler;
use OniBus\Buses\DispatchToHandler;
use OniBus\Command\CommandBus;
use OniBus\Handler\Builder\Resolver;

$resolver = 
    Resolver::new(new MyPsr11Container())
        ->withHandlers([UserRegistrationHandler::class])
        ->mapByAttributes(CommandHandler::class);

$commandBus = new CommandBus(new DispatchToHandler($resolver));

$userDTO = $commandBus->dispatch(new UserRegistrationCommand('username', 'secret'));
```


### Query Bus

```php
<?php
use OniBus\Query\Query;   

class UserByStatus implements Query
{
    protected $status;
    
    public function __construct(string $status)
    {
           $this->status = $status;
    }
    
    public function getStatus(): string
    {
        return $this->status;
    }
}
```

```php
<?php
use OniBus\Attributes\QueryHandler;

class FindUserByStatusSQL
{
    #[QueryHandler]
    public function fetch(UserByStatus $query)
    {
        return [/* ... */];
    }
}
```

```php
<?php
use OniBus\Attributes\QueryHandler;
use OniBus\Buses\DispatchToHandler;
use OniBus\Query\QueryBus;
use OniBus\Handler\Builder\Resolver;

$resolver = 
    Resolver::new(new MyPsr11Container())
        ->withHandlers([FindUserByStatusSQL::class])
        ->mapByAttributes(QueryHandler::class);

$queryBus = new QueryBus(new DispatchToHandler($resolver));

$users = $queryBus->dispatch(new UserByStatus('active'));
```

### EventBus Bus

```php
<?php
use OniBus\Event\Event;  

class UserCreatedEvent implements Event
{
    protected $username;
    
    public function __construct(string $username)
    {
           $this->username = $username;
    }
    
    public function getUsername(): string
    {
        return $this->username;
    }
}
```

```php
<?php
use OniBus\Attributes\EventListener;

class CreateUserProfile
{
    #[EventListener]
    public function createProfile(UserCreatedEvent $event)
    {
        /* ... */
    }
}
```

```php
<?php
use OniBus\Attributes\EventListener;
use OniBus\Buses\DispatchToHandler;
use OniBus\Event\EventBus;
use OniBus\Handler\Builder\Resolver;

$resolver = 
    Resolver::new(new MyPsr11Container())
        ->withHandlers([CreateUserProfile::class])
        ->mapByAttributes(EventListener::class);

$eventBus = new EventBus(new DispatchToHandler($resolver));

$eventBus->dispatch(new UserCreatedEvent('username'));
```


### Bus Chain

```php
<?php

/* CONTAINER */
$container = new MyPsr11Container();

/* EVENT BUS */
$eventResolver = 
    Resolver::new($container)
        ->withHandlers([CreateUserProfile::class])
        ->mapByAttributes(EventListener::class);
       
$eventBus = new EventBus(new DispatchToHandler($eventResolver));

/* COMMAND BUS */
$commandResolver = 
    Resolver::new($container)
        ->withHandlers([UserRegistrationHandler::class])
        ->mapByAttributes(CommandHandler::class);

$commandBus = 
     new CommandBus(
        new EventsDispatcher(
            EventManager::eventProvider(),
            $eventBus,
        ),
        // new ProtectOrder(),
        // new Transactional(),
        new DispatchToHandler($commandResolver)
    );
```

### Container Configuration
```php
<?php
use OniBus\Command\CommandBus;

$container->add(CommandBus::class, $commandBus);
```
### Controller
```php
<?php

class UserRegistrationController
{
    protected $commandBus;
    
    public function __construct(CommandBus $commandBus) 
    {
        $this->commandBus = $commandBus;    
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = []): ResponseInterface
    {
        $body = $request->getParsedBody();
        $command = new UserRegistrationCommand($body['username'], $body['password']);
        $user = $this->commandBus->dispatch($command);
        $response->getBody()->write($user);
        return $response;
    }
```


## Contributing

Please read the Contributing guide to learn about contributing to this project.

## License

This project is licensed under the terms of the MIT license. See the [LICENSE](LICENSE.md) file for license rights and limitations.
