<?php

namespace Project\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Project\BookingBundle\Validator\Constraints as MyAssert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="Project\BookingBundle\Repository\TicketRepository")
 * @MyAssert\HourExceeds
 */
class Ticket
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
     * @var \DateTime
     *
     * @ORM\Column(name="dayToVisit", type="date")
     * @Assert\GreaterThanOrEqual("today", message="Vous ne pouvez pas réservé de billets pour un jour passé.")
     * @Assert\NotEqualTo("tuesday", message="Le musée est fermé le Mardi, le Dimanche, le 1er Mai, le 1er Novembre et le 25 Décembre.")
     * @Assert\NotEqualTo("sunday", message="Le musée est fermé le Mardi, le Dimanche, le 1er Mai, le 1er Novembre et le 25 Décembre.")
     * @MyAssert\Wrongday
     */
    private $dayToVisit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="typeOfTicket", type="boolean")
     */
    private $typeOfTicket;

    /**
     * @var int
     *
     * @ORM\Column(name="nbTickets", type="integer")
     * @Assert\NotBlank
     * @Assert\GreaterThan(0, message="Vous devez réserver au moins 1 billet.")
     */
    private $nbTickets;

    /**
     * @ORM\OneToOne(targetEntity="Project\BookingBundle\Entity\Command", inversedBy="ticket")
     * @ORM\JoinColumn(name="command_id", referencedColumnName="id")
     */
    private $command;

    
    public function __construct()
    {
        $this->dayToVisit = new \DateTime();
    }

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
     * Set dayToVisit
     *
     * @param \DateTime $dayToVisit
     *
     * @return Ticket
     */
    public function setDayToVisit($dayToVisit)
    {
        $this->dayToVisit = $dayToVisit;

        return $this;
    }

    /**
     * Get dayToVisit
     *
     * @return \DateTime
     */
    public function getDayToVisit()
    {
        return $this->dayToVisit;
    }

    /**
     * Set typeOfTicket
     *
     * @param boolean $typeOfTicket
     *
     * @return Ticket
     */
    public function setTypeOfTicket($typeOfTicket)
    {
        $this->typeOfTicket = $typeOfTicket;

        return $this;
    }

    /**
     * Get typeOfTicket
     *
     * @return boolean
     */
    public function getTypeOfTicket()
    {
        return $this->typeOfTicket;
    }

    /**
     * Set nbTickets
     *
     * @param integer $nbTickets
     *
     * @return Ticket
     */
    public function setNbTickets($nbTickets)
    {
        $this->nbTickets = $nbTickets;

        return $this;
    }

    /**
     * Get nbTickets
     *
     * @return integer
     */
    public function getNbTickets()
    {
        return $this->nbTickets;
    }

    /**
     * Set command
     *
     * @param \Project\BookingBundle\Entity\Command $command
     *
     * @return Ticket
     */
    public function setCommand(\Project\BookingBundle\Entity\Command $command = null)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return \Project\BookingBundle\Entity\Command
     */
    public function getCommand()
    {
        return $this->command;
    }
}
