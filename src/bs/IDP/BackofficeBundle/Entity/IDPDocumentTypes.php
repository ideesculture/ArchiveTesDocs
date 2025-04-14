<?php
namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPDocumentTypes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPDocumentTypesRepository")
 */
class IDPDocumentTypes
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
	 * @var integer
	 *
	 * @ORM\Column(name="keepaliveduration", type="integer")
	 */
	private $keepaliveduration;

	/**
	 * @ORM\ManyToMany( targetEntity="IDPDocumentNatures", inversedBy="documentTypes" )
	 **/
	private $documentNatures;


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
     * @return IDPDocumentTypes
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

	/**
	 * Set keepaliveduration
	 *
	 * @param integer $keepaliveduration
	 * @return IDPDocumentTypes
	 */
	public function setKeepaliveduration($keepaliveduration)
	{
		$this->keepaliveduration = $keepaliveduration;
		return $this;
	}

	/**
	 * Get keepaliveduration
	 *
	 * @return integer
	 */
	public function getKeepaliveduration()
	{
		return $this->keepaliveduration;
	}

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documentNatures = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add documentNatures
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentNatures
     * @return IDPDocumentTypes
     */
    public function addDocumentNature(\bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentNatures)
    {
        $this->documentNatures[] = $documentNatures;

        return $this;
    }

    /**
     * Remove documentNatures
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentNatures
     */
    public function removeDocumentNature(\bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentNatures)
    {
        $this->documentNatures->removeElement($documentNatures);
    }

    /**
     * Get documentNatures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentNatures()
    {
        return $this->documentNatures;
    }
}
