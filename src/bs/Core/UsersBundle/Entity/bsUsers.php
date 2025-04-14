<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * bsUsers
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\UsersBundle\Entity\bsUsersRepository")
 */
class bsUsers
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
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastaction", type="integer", nullable=true )
     */
    private $lastaction;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="connected", type="boolean" )
	 */
	private $connected;

    /**
     * @var boolean
     *
     * @ORM\Column(name="changepass", type="boolean")
     */
    private $changepass;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="failedconnexioncounter", type="integer")
	 */
	private $failedconnexioncounter;

    /**
     * @var string
     *
     * @ORM\Column(name="phpsessid", type="string", nullable=true )
     */
    private $phpsessid;

	/**
	 * @ORM\ManyToMany( targetEntity="bsRights", mappedBy="users" )
	 **/
	private $rights;

	/**
	 * @ORM\ManyToMany( targetEntity="bsRoles", mappedBy="users" )
	 **/
	private $roles;

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
     * Set firstname
     *
     * @param string $firstname
     * @return bsUsers
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return bsUsers
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return bsUsers
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return bsUsers
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set lastaction
     *
     * @param integer $lastaction
     * @return bsUsers
     */
    public function setLastaction($lastaction)
    {
        $this->lastaction = $lastaction;

        return $this;
    }

    /**
     * Get lastaction
     *
     * @return integer
     */
    public function getLastaction()
    {
        return $this->lastaction;
    }

	/**
	 * Set connected
	 *
	 * @param boolean $connected
	 * @return bsUsers
	 */
	public function setConnected($connected)
	{
		$this->connected = $connected;

		return $this;
	}

	/**
	 * Get connected
	 *
	 * @return boolean
	 */
	public function getConnected()
	{
		return $this->connected;
	}

	/**
     * Set changepass
     *
     * @param boolean $changepass
     * @return bsUsers
     */
    public function setChangepass($changepass)
    {
        $this->changepass = $changepass;

        return $this;
    }

    /**
     * Get changepass
     *
     * @return boolean
     */
    public function getChangepass()
    {
        return $this->changepass;
    }

    /**
     * Set failedconnexioncounter
     *
     * @param integer $failedconnexioncounter
     * @return bsUsers
     */
    public function setFailedconnexioncounter($failedconnexioncounter)
    {
        $this->failedconnexioncounter = $failedconnexioncounter;

        return $this;
    }

    /**
     * Get failedconnexioncounter
     *
     * @return integer
     */
    public function getFailedconnexioncounter()
    {
        return $this->failedconnexioncounter;
    }

    /**
     * Set phpsessid
     *
     * @param string $phpsessid
     * @return bsUsers
     */
    public function setPhpsessid( $phpsessid ){
        $this->phpsessid = $phpsessid;
        return $this;
    }
    /**
     * Get phpsessid
     *
     * @return string
     */
    public function getPhpsessid( ){
        return $this->phpsessid;
    }

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->rights = new \Doctrine\Common\Collections\ArrayCollection();
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
		$this->connected = false;
	}

	/**
	 * Add right
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsRights $rights
	 * @return bsUsers
	 */
	public function addRight(\bs\Core\UsersBundle\Entity\bsRights $right)
	{
		$this->rights[] = $right;

		return $this;
	}

	/**
	 * Remove right
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsRights $right
	 */
	public function removeRight(\bs\Core\UsersBundle\Entity\bsRights $right)
	{
		$this->rights->removeElement($right);
	}

	/**
	 * Get rights
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getRights()
	{
		return $this->rights;
	}

	/**
	 * Add role
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsRoles $role
	 * @return bsUsers
	 */
	public function addRole(\bs\Core\UsersBundle\Entity\bsRoles $role)
	{
		$this->roles[] = $role;

		return $this;
	}

	/**
	 * Remove role
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsRoles $role
	 */
	public function removeRole(\bs\Core\UsersBundle\Entity\bsRoles $role)
	{
		$this->roles->removeElement($role);
	}

	/**
	 * Get roles
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getRoles()
	{
		return $this->roles;
	}
}
