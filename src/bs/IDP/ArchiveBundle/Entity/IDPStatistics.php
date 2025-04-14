<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPStatistics
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPStatisticsRepository")
 */
class IDPStatistics
{
	const IDP_STATISTICS_MOVE_TRANSFER = 1;
	const IDP_STATISTICS_MOVE_CONSULT = 2;
	const IDP_STATISTICS_MOVE_RETURN = 3;
	const IDP_STATISTICS_MOVE_EXIT = 4;
	const IDP_STATISTICS_MOVE_DELETE = 5;
    const IDP_STATISTICS_MOVE_RELOC = 6;

	const IDP_STATISTICS_CONTAIN_CONTAINER = 1;
	const IDP_STATISTICS_CONTAIN_BOX = 2;
	const IDP_STATISTICS_CONTAIN_FILE = 3;

	const IDP_STATISTICS_WHERE_SERVICE = 1;
	const IDP_STATISTICS_WHERE_INTERMEDIATE = 2;
	const IDP_STATISTICS_WHERE_PROVIDER = 3;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="statcount", type="integer", nullable=false)
	 */
	private $statcount;

	/**
	 * @var integer
	 *
	 * @ORM\Column( name="statmove", type="integer", nullable=false )
	 * 1: Transfert
	 * 2: Consultation
	 * 3: Retour
	 * 4: Sortie définitive
	 * 5: Destruction
     * 6: Relocalisation
	 */
	private $statmove;

	/**
	 * @var integer
	 *
	 * @ORM\COLUMN( name="statyearmonth", type="integer", nullable=false )
	 * YYMM
	 */
	private $statyearmonth;

	/**
	 * @var integer
	 *
	 * @ORM\Column( name="statcontain", type="integer", nullable=false )
	 * 1: Conteneur
	 * 2: Boite
	 * 3: Dossier
	 */
	private $statcontain;

	/**
	 * @var where
	 *
	 * @ORM\Column( name="statwhere", type="integer", nullable=false )
	 * 1: Service
	 * 2: Intermédiaire
	 * 3: Prestataire
	 */
	private $statwhere;

	/**
     * @var statserviceid
     *
     * @ORM\Column( name="statserviceid", type="integer", nullable=true)
	 **/
	private $statserviceid;

	/**
     * @var statbudgetcodeid
     *
     * @ORM\Column( name="statbudgetcodeid", type="integer", nullable=true)
	 **/
	private $statbudgetcodeid;

	/**
     * @var statlegalentityid
     *
     * @ORM\Column( name="statlegalentityid", type="integer", nullable=true)
	 **/
	private $statlegalentityid;

	/**
     * @var statproviderid
     *
     * @ORM\Column( name="statproviderid", type="integer", nullable=true)
	 **/
	private $statprovider_id;

    /**
     * @var statlocalizationid
     *
     * @ORM\Column( name="statlocalizationid", type="integer", nullable=true)
     */
    private $statlocalizationid;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get count
	 *
	 * @return integer
	 */
	public function getCount()
	{
		return $this->statcount;
	}

	/**
	 * Set count
	 *
	 * @param integer count
	 * @return IDPStatistics
	 */
	public function setCount( $count )
	{
		$this->statcount = $count;
		return $this;
	}

	/**
	 * Get move
	 *
	 * @return integer
	 */
	public function getMove()
	{
		return $this->statmove;
	}

	/**
	 * Set move
	 * @param integer move
	 * @return IDPStatistics
	 */
	public function setMove( $move )
	{
		$this->statmove = $move;
		return $this;
	}

	/**
	 * Get contain
	 *
	 * @return integer
	 */
	public function getContain()
	{
		return $this->statcontain;
	}

	/**
	 * Set contain
	 * @param integer $contain
	 * @return IDPStatistics
	 */
	public function setContain( $contain )
	{
		$this->statcontain = $contain;
		return $this;
	}

	/**
	 * Get yearmonth
	 *
	 * @return integer
	 */
	public function getYearmonth()
	{
		return $this->statyearmonth;
	}

	/**
	 * set yearmonth
	 * @param integer $yearmonth
	 * @return IDPStatistics
	 */
	public function setYearmonth( $yearmonth )
	{
		$this->statyearmonth = $yearmonth;
		return $this;
	}

	/**
	 * Get where
	 *
	 * @return integer
	 */
	public function getWhere(){
		return $this->statwhere;
	}

	/**
	 * set where
	 * @param integer $where
	 * @return IDPStatistics
	 */
	public function setWhere( $where ){
		$this->statwhere = $where;
		return $where;
	}

	/**
	 * Set budgetcodeid
	 *
	 * @param integer $budgetcodeid
	 * @return IDPStatistics
	 */
	public function setBudgetcode($budgetcodeid = null)
	{
		$this->statbudgetcodeid = $budgetcodeid;

		return $this;
	}

	/**
	 * Get budgetcodeid
	 *
	 * @return integer
	 */
	public function getBudgetcode()
	{
		return $this->statbudgetcodeid;
	}

	/**
	 * Set statserviceid
	 *
	 * @param integer $serviceid
	 * @return IDPStatistics
	 */
	public function setService($serviceid = null)
	{
		$this->statserviceid = $serviceid;

		return $this;
	}

	/**
	 * Get serviceid
	 *
	 * @return integer
	 */
	public function getService()
	{
		return $this->statserviceid;
	}

	/**
	 * Set legalentityid
	 *
	 * @param integer $legalentityid
	 * @return IDPStatistics
	 */
	public function setLegalentity($legalentityid = null)
	{
		$this->statlegalentityid = $legalentityid;

		return $this;
	}

	/**
	 * Get legalentityid
	 *
	 * @return integer
	 */
	public function getLegalentity()
	{
		return $this->statlegalentityid;
	}

	/**
	 * Set providerid
	 *
	 * @param integer $providerid
	 * @return IDPStatistics
	 */
	public function setProvider($providerid = null)
	{
		$this->statprovider_id = $providerid;

		return $this;
	}

	/**
	 * Get providerid
	 *
	 * @return integer
	 */
	public function getProvider()
	{
		return $this->statproviderid;
	}

    /**
     * Set localizationid
     *
     * @param integer $localizationid
     * @return IDPStatistics
     */
    public function setLocalization($localizationid = null)
    {
        $this->statlocalization_id = $localizationid;

        return $this;
    }

    /**
     * Get localizationid
     *
     * @return integer
     */
    public function getLocalization()
    {
        return $this->statlocalizationid;
    }
}
