<?php

namespace bs\Core\TranslationBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * bsTranslationRepository
 *
 */
class bsTranslationRepository extends EntityRepository
{
	public function getArrayTranslation( $page, $language ){
		$query = $this->getEntityManager()
        	->createQuery("SELECT t.sentence, t.translation FROM bsCoreTranslationBundle:bsTranslation t WHERE t.page = $page AND t.language = $language ORDER BY t.sentence ASC")
			->setHint(Query::HINT_INCLUDE_META_COLUMNS, true);
		$ret = $query->getArrayResult();

		$_ret = array();
		foreach( $ret as $line ){
			$_ret [ $line['sentence'] ] = $line['translation'];
		}
		return $_ret;
	}
}
