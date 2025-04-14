<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * bsRoles
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\UsersBundle\Entity\bsRolesRepository")
 */
class bsRoles
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
	 * @ORM\COlumn(name="scale", type="integer" )
	 **/
	private $scale;

	/**
	 * @ORM\ManyToMany( targetEntity="bsUsers", inversedBy="roles" )
	 **/
	private $users;

	/**
	 * @ORM\ManyToMany( targetEntity="bsRights", mappedBy="roles" )
	 **/
	private $rights;


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
     * @return bsRoles
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
	 * Set scale
	 *
	 * @param integer $scale
	 * @return bsRoles
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

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return bsRoles
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
	 * Constructor
	 */
	public function __construct()
	{
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
		$this->rights = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Add user
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsUsers $user
	 * @return bsRole
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
	 * Add right
	 *
	 * @param \bs\Core\UsersBundle\Entity\bsRights $right
	 * @return bsRole
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



}
