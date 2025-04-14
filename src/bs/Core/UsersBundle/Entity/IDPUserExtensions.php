<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPUserExtensions
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\UsersBundle\Entity\IDPUserExtensionsRepository")
 */
class IDPUserExtensions
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
	 * @ORM\ManyToOne(targetEntity="\bs\Core\UsersBundle\Entity\bsUsers")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
	 **/
	private $user;

	/**
	 * @var string
	 *
	 * @ORM\Column( name="initials", type="string", length=4 )
	 **/
	private $initials;

	/**
	 * @var integer
	 *
	 * @ORM\Column( name="uacounter", type="integer" )
	 **/
	private $uacounter;

	/**
	 * @var integer
	 *
	 * @ORM\Column( name="language", type="integer" )
	 */
	private $language;

	/**
	 * Set user
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsUsers $user
	 * @return IDPUserExtensions
	 */
	public function setUser(\bs\Core\UsersBundle\Entity\bsUsers $user)
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * Get user
	 *
	 * @return \bs\Core\UsersBundle\Entity\bsUsers
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set initials
	 *
	 * @param string $initials
	 * @return IDPUserExtensions
	 */
	public function setInitials($initials)
	{
		$this->initials = $initials;

		return $this;
	}

	/**
	 * Get initials
	 *
	 * @return string
	 */
	public function getInitials()
	{
		return $this->initials;
	}

	/**
	 * Set uacounter
	 *
	 * @param integer $uacounter
	 * @return IDPUserExtensions
	 */
	public function setUacounter( $uacounter )
	{
		$this->uacounter = $uacounter;

		return $this;
	}

	/**
	 * Get uacounter
	 *
	 * @return integer
	 */
	public function getUacounter( )
	{
		return $this->uacounter;
	}

	/**
	 * Set language
	 *
	 * @param integer $language
	 * @return IDPUserExtensions
	 */
	public function setLanguage( $language )
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * Get language
	 *
	 * @return integer
	 */
	public function getLanguage( )
	{
		return $this->language;
	}
}
