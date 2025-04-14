<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IDPImportComm
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPImportCommRepository")
 */
class IDPImportComm
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
     * @var integer
     *
     * @ORM\Column(name="import_id", type="integer")
     */
    private $import_id;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent", type="integer")
	 */
	private $percent;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="message", type="string", length=255, nullable=true)
	 */
	private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="rawline", type="string", length=2048, nullable=true)
     */
    private $raw_line;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="status", type="integer")
	 */
	private $status;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="alreadyRead", type="integer")
	 */
	private $alreadyRead;

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
     * Set import_id
     *
     * @param integer $import_id
     * @return IDPImportComm
     */
    public function setImportId( $import_id )
    {
        $this->import_id = $import_id;

        return $this;
    }

    /**
     * Get Import_id
     *
     * @return integer
     */
    public function getImportId()
    {
        return $this->import_id;
    }

    /**
	 * Set percent
	 *
	 * @param integer $percent
	 * @return IDPImportComm
	 */
	public function setPercent( $percent )
	{
		$this->percent = $percent;

		return $this;
	}

	/**
	 * Get Percent
	 *
	 * @return integer
	 */
	public function getPercent()
	{
		return $this->percent;
	}

	/**
	 * Set message
	 *
	 * @param string $message
	 * @return IDPImportComm
	 */
	public function setMessage( $message )
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * Get message
	 *
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

    /**
     * Set raw_line
     *
     * @param string $raw_line
     * @return IDPImportComm
     */
    public function setRawLine( $raw_line )
    {
        $this->raw_line = $raw_line;

        return $this;
    }

    /**
     * Get raw_line
     *
     * @return string
     */
    public function getRawLine()
    {
        return $this->raw_line;
    }

	/**
	 * Set status
	 *
	 * @param integer $status
	 * @return IDPImportComm
	 */
	public function setStatus( $status )
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Get Status
	 *
	 * @return integer
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Set alreadyRead
	 *
	 * @param integer $alreadyRead
	 * @return IDPImportComm
	 */
	public function setAlreadyRead( $alreadyRead )
	{
		$this->alreadyRead = $alreadyRead;

		return $this;
	}

	/**
	 * Get read
	 *
	 * @return integer
	 */
	public function getAlreadyRead()
	{
		return $this->alreadyRead;
	}
}
