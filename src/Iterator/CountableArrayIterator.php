<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Iterator;

use ArrayIterator;

/**
 * @template TKey of int|string
 * @template TValue
 * @extends ArrayIterator<TKey, TValue>
 */
final class CountableArrayIterator extends ArrayIterator
{

	private int $offset = 0;

	public function next(): void
	{
		$this->offset++;

		parent::next();
	}

	public function rewind(): void
	{
		$this->offset = 0;

		parent::rewind();
	}

	public function getOffset(): int
	{
		return $this->offset;
	}

}
