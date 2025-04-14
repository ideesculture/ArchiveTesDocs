<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPUserAddresses
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\UsersBundle\Entity\IDPUserAddressesRepository")
 */
class IDPUserAddresses
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
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPDeliverAddress")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     **/
    private $address;

    /**
     * Set user
     *
     * @param \bs\Core\UsersBundle\Entity\bsUsers $user
     * @return IDPUserAddresses
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
     * Set address
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDeliverAddress $address
     * @return IDPUserAddresses
     */
    public function setAddress(\bs\IDP\BackofficeBundle\Entity\IDPDeliverAddress $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPDeliverAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

}
