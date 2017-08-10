<?php

namespace Indexed\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Indexed\SubscriptionBundle\IndexedSubscriptionBundle;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class RestLog
{
    const ACTION_DELETE = 'DELETE';
    const ACTION_GET = 'GET';
    const ACTION_HEAD = 'HEAD';
    const ACTION_POST = 'POST';
    const ACTION_PATCH = 'PATCH';
    const ACTION_PUT = 'PUT';
    const ACTION_OPTIONS = 'OPTIONS';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $method;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $resource;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $request;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $response;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $error = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Indexed\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var \Indexed\SubscriptionBundle\Entity\Company
     *
     * @ORM\ManyToOne(targetEntity="\Indexed\SubscriptionBundle\Entity\Company")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="Indexed\SubscriptionBundle\Entity\Subscription")
     */
    private $subscription;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return RestLog
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \Indexed\UserBundle\Entity\User $user
     * @return RestLog
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Indexed\UserBundle\Entity\Customer
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->setCreatedAt(new \DateTime());
        }
    }

    /**
     * Set company
     *
     * @param \Indexed\SubscriptionBundle\Entity\Company $company
     * @return $this
     */
    public function setCompany(\Indexed\SubscriptionBundle\Entity\Company $company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Indexed\SubscriptionBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return \Indexed\SubscriptionBundle\Entity\Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param \Indexed\SubscriptionBundle\Entity\Subscription $subscription
     * @return RestLog
     */
    public function setSubscription(\Indexed\SubscriptionBundle\Entity\Subscription $subscription)
    {
        $this->subscription = $subscription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     * @return RestLog
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     * @return RestLog
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     * @return RestLog
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     * @return RestLog
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     * @return RestLog
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

}