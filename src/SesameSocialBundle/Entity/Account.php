<?php

namespace SesameSocialBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Entity model for a user account
 *
 * @author Samira Musabbir <samira.musabbir@sesamecommunications.com>
 * @copyright 2017 Sesame Communications
 *
 * @ORM\Entity
 * @ORM\Table(name="account")
 *
 * @UniqueEntity({"email"})
 * @UniqueEntity({"apiKey"})
 */

class Account
{
    /**
     * Record ID
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     *
     * @var integer
     */
    private $id;

    /**
     * User's unique email address (functions as username)
     *
     * @ORM\Column(name="email", type="string", length=191, unique=true)
     *
     * @var string
     */
    private $email;

    /**
     * User's first name
     *
     * @ORM\Column(name="first_name", type="string", length=75)
     *
     * @var string
     */
    private $firstName;

    /**
     * User's last name
     *
     * @ORM\Column(name="last_name", type="string", length=75)
     *
     * @var string
     */
    private $lastName;

    /**
     * User's unique API key;
     *
     * @ORM\Column(name="api_key", type="string", length=100, unique=true, nullable=true)
     *
     * @var string
     */
    private $apiKey;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Account
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Account
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getfirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $name
     * @return Account
     */
    public function setfirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getlastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $name
     * @return Account
     */
    public function setlastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }


    /**
     * @return string
     */
    public function getapiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $name
     * @return Account
     */
    public function setapiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }



}