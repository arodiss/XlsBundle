<?php
namespace Arodiss\XlsBundle\Iterator;

abstract class AbstractWrappingIterator implements \Iterator {

	/** @var \Iterator */
	protected $iterator;

	/** @param \Iterator $iterator */
	public function __construct(\Iterator $iterator) {
		$this->iterator = $iterator;
	}

	/** {@inheritdoc} */
	public function next() {
		$this->iterator->next();
	}

	/** {@inheritdoc} */
	public function rewind() {
		$this->iterator->rewind();
	}

	/** {@inheritdoc} */
	public function valid() {
		return $this->iterator->valid();
	}

	/** {@inheritdoc} */
	public function key() {
		return $this->iterator->key();
	}
}
