# Event Dispatcher
[![CircleCI](https://circleci.com/gh/NathanBurkett/event-dispatcher.svg?style=svg)](https://circleci.com/gh/NathanBurkett/event-dispatcher)
[![codecov](https://codecov.io/gh/NathanBurkett/event-dispatcher/branch/master/graph/badge.svg)](https://codecov.io/gh/NathanBurkett/event-dispatcher)

A [PSR-14](https://www.php-fig.org/psr/psr-14/) implementation which uses a [Resolver](src/DI/) to inject dependencies from a container when resolving EventListeners.

A [SortingAlgorithm](src/Sort/SortingAlgorithm.php) prioritizes event->listener [Subscriptions](src/Subscription/Subscription.php) at execution time. Priortization occurs in [ListenerProvider](https://github.com/NathanBurkett/event-dispatcher/blob/863dfd208167435f6be6ec27e7af51958e8a1976/src/Listener/Provider/ListenerProvider.php#L119) and observes priority from highest to lowest.

This means a ListenerProvider can already have multiple Subscriptions but could [add another](https://github.com/NathanBurkett/event-dispatcher/blob/863dfd208167435f6be6ec27e7af51958e8a1976/src/Listener/Provider/ListenerProvider.php#L54) at run time whose priority facilitates the EventListener being handled first:

```php
<?php 

$provider->add(new Subscription(Event::class, EventListener::class, 999);
``` 

## Installation

Via Composer

```bash
composer require nathanburkett/event-dispatcher
```

## Usage

Events are paired with EventListeners through [Subscriptions](src/Subscription/Subscription.php). Subscriptions take an Event class, a Listener class, and an optional priority assignment of where the Listener should be called in relation to other Listeners for the same Event in a ListenerProvider.

[ListenerProviders](src/Listener/Provider/ListenerProvider.php) hold Subscriptions and are also responsible for prioritizing and resolving Listeners so their callable is consumable.

An [EventDispatcher](src/EventDispatcher.php) contains multiple ListenerProviders and can [attach new ones](https://github.com/NathanBurkett/event-dispatcher/blob/master/src/EventDispatcher.php#L17) before or during execution.
The EventDispatcher facilitates an Event moving through the EventListener invocation pipeline and will halt further EventListener calls if the Event's [propagation should be stopped](https://github.com/NathanBurkett/event-dispatcher/blob/master/src/EventDispatcher.php#L47-L49).

An [EventEmitter](src/EventEmitter/EventEmitter.php) simply acts as a courier for Events [making their way to an EventDispatcher](https://github.com/NathanBurkett/event-dispatcher/blob/863dfd208167435f6be6ec27e7af51958e8a1976/src/EventEmitter/EventEmitter.php#L28).

### Basic Implementation

```php
<?php

// ContainerReflectionResolver will instantiate an EventListener by resolving any dependencies from a ContainerInterface
$resolver = new ContainerReflectionResolver($container);

// DescendingSubscriptionComparator ensures proper ordering of Subscriptions inside a SortingAlgorithm
$comparator = new DescendingSubscriptionComparator();
$sorter = new SortingAlgorithm($comparator);

$provider = new ListenerProvider($resolver, $sorter);
```

Then a ListenerProvider can track Subscriptions:
```php
<?php

// SendValidateEmailMail could have a Mailer already existing in the container as a dependency in
// its constructor
$sendValidateEmail = new Subscription(NewUserRegistered::class, SendValidateEmailMail::class);
// CompleteNewUserProfile could have a ProfileRepository already existing in the container as a
// dependency in its constructor
$completeUserProfile = new Subscription(NewUserRegistered::class, CompleteNewUserProfile::class);

$provider->addSubscription($sendValidateEmail);
$provider->addSubscription($completeUserProfile);
```

An EventDispatcher must be made aware of the new ListenerProvider
```php
<?php

$dispatcher = new EventDispatcher();
// or resolve an EventDispatcher singleton from container
// $dispatcher = $this->container->get(EventDispatcherInterface::class);

$dispatcher->addListenerProvider($provider);
```

And finally an EventEmitter needs to consume the EventDispatcher and is ready to send Events
```php
<?php

$emitter = new EventEmitter($dispatcher);

// ...

$user = new User('Nathan Burkett', 'someemail@foobarbaz.com');

$event = new NewUserRegistered($user);

$emitter->emit($event);
```
