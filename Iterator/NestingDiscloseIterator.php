<?php
namespace Arodiss\XlsBundle\Iterator;

class NestingDiscloseIterator extends AbstractWrappingIterator {

	/** {@inheritdoc} */
	public function current($disclose = true) {
		$cellIterator = $this->iterator->current()->getCellIterator();
		if ($disclose) {
			$current = array();
			while($cellIterator->valid()) {
				$current[] = $cellIterator->current();
				$cellIterator->next();
			}
			return $current;
		} else {
			return $cellIterator;
		}
	}
}
