<?php declare(strict_types = 1);

use Tester\Assert;
use WebChemistry\Eventer\Message\GoogleEventMessage;
use WebChemistry\Eventer\Message\GoogleSetMessage;

require __DIR__ . '/../bootstrap.php';

$googleEvent = new GoogleEventMessage('foo', ['key' => 'bar']);

Assert::same('gtag', $googleEvent->getRecipient());
Assert::same(['event', 'foo', ['key' => 'bar']], $googleEvent->getContent());

$googleSet = new GoogleSetMessage('foo', 'bar');

Assert::same('gtag', $googleSet->getRecipient());
Assert::same(['set', 'foo', 'bar'], $googleSet->getContent());
