<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * bsRights
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\UsersBundle\Entity\bsRightsRepository")
 */
class bsRights
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="string", length=1024)
	 */
	private $description;

	/**
	 * @var integer
	 *
	 * @ORM\Column( name="scale", type="integer")
	 */
	private $scale;

	/**
	 * @ORM\ManyToMany( targetEntity="bsUsers", inversedBy="rights" )
	 **/
	private $users;

	/**
	 * @ORM\ManyToMany( targetEntity="bsRoles", inversedBy="rights" )
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
     * Set name
     *
     * @param string $name
     * @return bsRights
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return bsRights
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set scale
	 *
	 * @param integer $scale
	 * @return bsRights
	 */
	public function setScale($scale)
	{
		$this->scale = $scale;

		return $this;
	}

	/**
	 * Get scale
	 *
	 * @return integer
	 */
	public function getScale()
	{
		return $this->scale;
	}

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Add user
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsUsers $user
	 * @return bsRights
	 */
	public function addUser(\bs\Core\UsersBundle\Entity\bsUsers $user)
	{
		$this->users[] = $user;

		return $this;
	}

	/**
	 * Remove user
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsUsers $user
	 */
	public function removeUser(\bs\Core\UsersBundle\Entity\bsUsers $user)
	{
		$this->users->removeElement($user);
	}

	/**
	 * Get users
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * Add role
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsRoles $role
	 * @return bsRights
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
