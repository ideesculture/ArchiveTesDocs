<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPUserServices
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\UsersBundle\Entity\IDPUserServicesRepository")
 */
class IDPUserServices
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
	 * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPServices")
	 * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
	 **/
	private $service;

	/**
	 * Set user
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsUsers $user
	 * @return IDPUserSeeServices
	 */
	public function setUser(\bs\Core\UsersBundle\Entity\bsUsers $user = null)
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
	 * Set service
	 *
	 * @param \bs\IDP\BackofficeBundle\Entity\IDPServices $service
	 * @return IDPUserSeeServices
	 */
	public function setService(\bs\IDP\BackofficeBundle\Entity\IDPServices $service = null)
	{
		$this->service = $service;

		return $this;
	}

	/**
	 * Get service
	 *
	 * @return \bs\IDP\BackofficeBundle\Entity\IDPServices
	 */
	public function getService()
	{
		return $this->service;
	}

}
