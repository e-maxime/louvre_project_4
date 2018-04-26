<?php

namespace Project\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="Project\BookingBundle\Repository\TicketRepository")
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date")
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

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
     * @Assert\NotEqualTo("tuesday", "sunday", message="Le musée est fermé le Mardi, le 1er Mai, le 1er Novembre et le 25 Décembre.")
     */
    private $dayToVisit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="typeOfTicket", type="boolean")
     */
    private $typeOfTicket;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reducePrice", type="boolean")
     */
    private $reducePrice = false;

    /**
     * @var int
     *
     * @ORM\Column(name="nbTickets", type="integer")
     * @Assert\NotBlank
     */
    private $nbTickets;
    
    /**
     * @ORM\ManyToMany(targetEntity="Project\BookingBundle\Entity\Visitor", cascade={"persist"})
     */
    private $visitors;

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
     * Set name
     *
     * @param string $name
     *
     * @return Form
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Form
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Form
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Form
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
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
     * Set dayToVisit
     *
     * @param \DateTime $dayToVisit
     *
     * @return Form
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
     * @param string $typeOfTicket
     *
     * @return Form
     */
    public function setTypeOfTicket($typeOfTicket)
    {
        $this->typeOfTicket = $typeOfTicket;

        return $this;
    }

    /**
     * Get typeOfTicket
     *
     * @return string
     */
    public function getTypeOfTicket()
    {
        return $this->typeOfTicket;
    }

    /**
     * Set reducePrice
     *
     * @param boolean $reducePrice
     *
     * @return Form
     */
    public function setReducePrice($reducePrice)
    {
        $this->reducePrice = $reducePrice;

        return $this;
    }

    /**
     * Get reducePrice
     *
     * @return boolean
     */
    public function getReducePrice()
    {
        return $this->reducePrice;
    }

    /**
     * Add visitor
     *
     * @param \Project\BookingBundle\Entity\Visitor $visitor
     *
     * @return Ticket
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
}
