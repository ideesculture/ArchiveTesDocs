<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;


/**
 * IDPStatisticsRepository
 *
 */
class IDPStatisticsRepository extends EntityRepository
{
	private function calculateDateSearch( $begin, $begintype, $increment ){
		$year = (int)($begin / 100);
		$month = (int)($begin - $year * 100);

		if( $begintype == 1 ){	// Month type
			while( $increment > 0 ){
				if( $increment > 12 )
					$delta = 12;
				else
					$delta = $increment;
				$increment = $increment - $delta;

				$month = $month + $delta;
				if( $month > 12 ){
					$year = $year + 1;
					$month = $month - 12;
				}
			}

			return (int)($year * 100 + $month );
		} else {	// Year type
			return (int)(($year + $increment)*100 + 12 );
		}
	}

	public function getGlobalDatas( $begin, $begintype, $length, $move, $service, $provider, $where, $legalentity, $budgetcode, $localization ){
		if( $begintype == 1 ){
			return $this->getGlobalDatasByMonth( $begin, $length, $move, $service, $provider, $where, $legalentity, $budgetcode, $localization );
		} else {
			return $this->getGlobalDatasByYear( $begin, $length, $move, $service, $provider, $where, $legalentity, $budgetcode, $localization );
		}
	}

	private function getGlobalDatasByMonth( $begin, $length, $move, $service, $provider, $where, $legalentity, $budgetcode, $localization ){
		$maxDateSearch = $this->calculateDateSearch( $begin, 1, $length );

		$querySelect = 'SELECT s.statyearmonth, s.statcontain';
		if( $where < 0 )
			$querySelect .= ', s.statwhere';
		$querySelect .= ', SUM (s.statcount) ';
		$querySelect .= ' FROM bsIDPArchiveBundle:IDPStatistics s ';
		$querySelect .= ' WHERE s.statmove = '. $move . ' ';
		$querySelect .= ' AND s.statyearmonth >= '. $begin . ' ';
		$querySelect .= ' AND s.statyearmonth <= '. $maxDateSearch . ' ';
		if( $where > 0 )
			$querySelect .= ' AND s.statwhere = '. $where . ' ';
		if( $service > 0 )
			$querySelect .= ' AND s.statserviceid = '. $service . ' ';
		if( $provider > 0 )
			$querySelect .= ' AND s.statproviderid = '. $provider . ' ';
		if( $budgetcode > 0 )
			$querySelect .= ' AND s.statbudgetcodeid = '. $budgetcode . ' ';
		if( $legalentity > 0 )
			$querySelect .= ' AND s.statlegalentityid = '. $legalentity . ' ';
        if( $localization > 0 )
            $querySelect .= ' AND s.statlocalizationid = '. $localization . ' ';

		$querySelect .= ' GROUP BY s.statyearmonth, s.statcontain';
		if( $where < 0 )
			$querySelect .= ', s.statwhere';

		$query = $this->getEntityManager()->createQuery( $querySelect );

		return $query->getResult();
	}
	private function getGlobalDatasByYear( $begin, $length, $move, $service, $provider, $where, $legalentity, $budgetcode, $localization ){

		$result = array();
		for( $i=0; $i<$length; $i++ ){
			$yearBegin = (intval($begin)-2000 + $i) * 100 + 1;
			$yearEnd = $yearBegin+11;

			$querySelect = 'SELECT SUM (s.statcount) ';
			$querySelect .= ' FROM bsIDPArchiveBundle:IDPStatistics s ';
			$querySelect .= ' WHERE s.statmove = '. $move . ' ';
			$querySelect .= ' AND s.statyearmonth >= '. $yearBegin . ' ';
			$querySelect .= ' AND s.statyearmonth <= '. $yearEnd . ' ';
			if( $where > 0 )
				$querySelect .= ' AND s.statwhere = '. $where . ' ';
			if( $service > 0 )
				$querySelect .= ' AND s.statserviceid = '. $service . ' ';
			if( $provider > 0 )
				$querySelect .= ' AND s.statproviderid = '. $provider . ' ';
			if( $budgetcode > 0 )
				$querySelect .= ' AND s.statbudgetcodeid = '. $budgetcode . ' ';
			if( $legalentity > 0 )
				$querySelect .= ' AND s.statlegalentityid = '. $legalentity . ' ';
            if( $localization > 0 )
                $querySelect .= ' AND s.statlocalizationid = '. $localization . ' ';

			$query = $this->getEntityManager()->createQuery( $querySelect );
			$_res = $query->getResult();

			$line = array( 'statdate' => sprintf( "%d", ($yearBegin-1)/100+2000 ), 'statcontain' => 0, '1' => intval($_res[0]['1']) );

			array_push( $result, $line );
		}
		return $result;
	}

	public function getDetailledDatas( $begin, $begintype, $length, $move, $service, $provider, $contain, $where, $legalentity, $budgetcode, $localization ){
		if( $begintype == 1 ){
			return $this->getDetailledDatasByMonth( $begin, $length, $move, $service, $provider, $contain, $where, $legalentity, $budgetcode, $localization );
		} else {
			return $this->getDetailledDatasByYear( $begin, $length, $move, $service, $provider, $contain, $where, $legalentity, $budgetcode, $localization );
		}
	}

	private function getDetailledDatasByMonth( $begin, $length, $move, $service, $provider, $contain, $where, $legalentity, $budgetcode, $localization ){
		$maxDateSearch = $this->calculateDateSearch( $begin, 1, $length );

		$querySelect = 'SELECT s.statyearmonth';
		if( $where < 0 )
			$querySelect .= ', s.statwhere';
		if( $contain < 0 )
			$querySelect .= ', s.statcontain';
		$querySelect .= ', SUM (s.statcount) ';
		$querySelect .= ' FROM bsIDPArchiveBundle:IDPStatistics s ';
		$querySelect .= ' WHERE s.statmove = '. $move . ' ';
		$querySelect .= ' AND s.statyearmonth >= '. $begin . ' ';
		$querySelect .= ' AND s.statyearmonth <= '. $maxDateSearch . ' ';
		if( $contain > 0 )
			$querySelect .= ' AND s.statcontain = '. $contain . ' ';
		if( $where > 0 )
			$querySelect .= ' AND s.statwhere = '. $where . ' ';
		if( $service > 0 )
			$querySelect .= ' AND s.statserviceid = '. $service . ' ';
		if( $provider > 0 )
			$querySelect .= ' AND s.statproviderid = '. $provider . ' ';
		if( $budgetcode > 0 )
			$querySelect .= ' AND s.statbudgetcodeid = '. $budgetcode . ' ';
		if( $legalentity > 0 )
			$querySelect .= ' AND s.statlegalentityid = '. $legalentity . ' ';
        if( $localization > 0 )
            $querySelect .= ' AND s.statlocalizationid = '. $localization . ' ';

		$querySelect .= ' GROUP BY s.statyearmonth';
		if( $where < 0 )
			$querySelect .= ', s.statwhere';
		if( $contain < 0 )
			$querySelect .= ', s.statcontain';

		$query = $this->getEntityManager()->createQuery( $querySelect );

		return $query->getResult();
	}
	private function getDetailledDatasByYear( $begin, $length, $move, $service, $provider, $contain, $where, $legalentity, $budgetcode, $localization ){
		$result = array();
		for( $i=0; $i<$length; $i++ ){
			$yearBegin = (intval($begin)-2000 + $i) * 100 + 1;
			$yearEnd = $yearBegin+11;

			$querySelect = 'SELECT SUM (s.statcount) ';
			$querySelect .= ' FROM bsIDPArchiveBundle:IDPStatistics s ';
			$querySelect .= ' WHERE s.statmove = '. $move . ' ';
			$querySelect .= ' AND s.statyearmonth >= '. $yearBegin . ' ';
			$querySelect .= ' AND s.statyearmonth <= '. $yearEnd . ' ';
			if( $contain > 0 )
				$querySelect .= ' AND s.statcontain = '. $contain . ' ';
			if( $where > 0 )
				$querySelect .= ' AND s.statwhere = '. $where . ' ';
			if( $service > 0 )
				$querySelect .= ' AND s.statserviceid = '. $service . ' ';
			if( $provider > 0 )
				$querySelect .= ' AND s.statproviderid = '. $provider . ' ';
			if( $budgetcode > 0 )
				$querySelect .= ' AND s.statbudgetcodeid = '. $budgetcode . ' ';
			if( $legalentity > 0 )
				$querySelect .= ' AND s.statlegalentityid = '. $legalentity . ' ';
            if( $localization > 0 )
                $querySelect .= ' AND s.statlocalizationid = '. $localization . ' ';

			$query = $this->getEntityManager()->createQuery( $querySelect );
			$_res = $query->getResult();

			$line = array( 'statdate' => sprintf( "%d", ($yearBegin-1)/100+2000 ), 'statcontain' => 0, 'where' => 0, '1' => intval($_res[0]['1']) );

			array_push( $result, $line );
		}
		return $result;
	}

	public function getStatistics( $yearmonth, $budgetcode, $legalentity, $where, $provider, $service, $contain, $move, $localization ){
		$queryString = 'SELECT s ';
		$queryString .= ' FROM bsIDPArchiveBundle:IDPStatistics s ';
		$queryString .= ' WHERE s.statyearmonth = '. $yearmonth;
		if( $budgetcode )
			$queryString .= ' AND s.statbudgetcodeid = '.$budgetcode;
		else
			$queryString .= ' AND s.statbudgetcodeid is NULL ';
		if( $legalentity )
			$queryString .= ' AND s.statlegalentityid = '.$legalentity;
		else
			$queryString .= ' AND s.statlegalentityid is NULL ';
		$queryString .= ' AND s.statwhere = '.$where;
		if( $provider )
			$queryString .= ' AND s.statproviderid = '.$provider;
		else
			$queryString .= ' AND s.statproviderid is NULL ';
		if( $service )
			$queryString .= ' AND s.statserviceid = '.$service;
		else
			$queryString .= ' AND s.statserviceid is NULL ';
        if( $localization )
            $queryString .= ' AND s.statlocalizationid = '. $localization;
        else
            $queryString .= ' AND s.statlocalizationid is NULL ';
        
		$queryString .= ' AND s.statcontain = '.$contain;
		$queryString .= ' AND s.statmove = '.$move;

		$query = $this->getEntityManager()->createQuery( $queryString );
		return $query->getOneOrNullResult();
	}

}



