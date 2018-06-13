<?php

namespace Project\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Project\BookingBundle\Validator\Constraints as MyAssert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="Project\BookingBundle\Repository\BookingRepository")
 * @MyAssert\HourExceeds
 * @MyAssert\MaxTicketsSold
 *
 */
class Booking
{
    const PRICE_FREE = 0;
    const PRICE_CHILD = 8;
    const PRICE_NORMAL = 16;
    const PRICE_OLD = 12;
    const PRICE_REDUCED = 10;
    const PRICE_HALF_DAY_MULTIPLICATOR = 0.5;
    const TYPE_HALF_DAY = false;
    const TYPE_FULL_DAY = true;

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
     * @var Visitor[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Project\BookingBundle\Entity\Visitor", mappedBy="booking", cascade={"persist"})
     */
    private $visitors;

    /**
     * @var float
     * @ORM\Column(name="totalPrice", type="float")
     */
    private $totalPrice;
    
    public function __construct()
    {
        $this->dayToVisit = new \DateTime();
        $this->visitors = new ArrayCollection();
        $this->typeOfTicket = Booking::TYPE_FULL_DAY;
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
     * @return Booking
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
     * @return Booking
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
     * @return Booking
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
     * Set email
     *
     * @param string $email
     *
     * @return Booking
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
     * Add visitor
     *
     * @param Visitor $visitor
     *
     * @return Booking
     */
    public function addVisitor(Visitor $visitor)
    {
        $this->visitors[] = $visitor;

        $visitor->setBooking($this);

        return $this;
    }

    /**
     * Remove visitor
     *
     * @param Visitor $visitor
     */
    public function removeVisitor(Visitor $visitor)
    {
        $this->visitors->removeElement($visitor);
    }

    /**
     * Get visitors
     *
     * @return Visitor[]|ArrayCollection
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * Set totalPrice
     *
     * @param float $totalPrice
     *
     * @return Booking
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }
}
