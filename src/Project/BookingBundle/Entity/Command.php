<?php

namespace Project\BookingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="Project\BookingBundle\Repository\CommandRepository")
 */
class Command
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="Project\BookingBundle\Entity\Visitor", cascade={"persist"})
     */
    private $visitors;

    /**
     * @ORM\OneToOne(targetEntity="Project\BookingBundle\Entity\Ticket", mappedBy="command", cascade={"persist"})
     * 
     */
    private $ticket;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Form
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visitors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add visitor
     *
     * @param \Project\BookingBundle\Entity\Visitor $visitor
     *
     * @return Command
     */
    public function addVisitor(\Project\BookingBundle\Entity\Visitor $visitor)
    {
        $this->visitors[] = $visitor;

        return $this;
    }

    /**
     * Remove visitor
     *
     * @param \Project\BookingBundle\Entity\Visitor $visitor
     */
    public function removeVisitor(\Project\BookingBundle\Entity\Visitor $visitor)
    {
        $this->visitors->removeElement($visitor);
    }

    /**
     * Get visitors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitors()
    {
        return $this->visitors;
    }


    /**
     * Set ticket
     *
     * @param \Project\BookingBundle\Entity\Ticket $ticket
     *
     * @return Command
     */
    public function setTicket(\Project\BookingBundle\Entity\Ticket $ticket = null)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \Project\BookingBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }
}
