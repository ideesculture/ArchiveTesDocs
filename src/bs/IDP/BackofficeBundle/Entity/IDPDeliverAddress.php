<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPDeliverAddress
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPDeliverAddressRepository")
 */
class IDPDeliverAddress
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="longname", type="string", length=255)
	 */
	private $longname;

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
	 * Set longname
	 *
	 * @param string $longname
	 * @return IDPDeliverAddress
	 */
	public function setLongname($longname)
	{
		$this->longname = $longname;

		return $this;
	}

	/**
	 * Get longname
	 *
	 * @return string
	 */
	public function getLongname()
	{
		return $this->longname;
	}

}
