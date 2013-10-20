<?php
namespace Arodiss\XlsBundle\Iterator;

class StringifyIterator extends AbstractWrappingIterator {
	/** {@inheritdoc} */
	public function current($stringify = true) {
		$value = $this->iterator->current();
		if ($stringify) {
			array_walk($value, function(&$value) {
				$value = (string) $value;
			});
		}
		return $value;
	}
}
