<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="fk_User_Group1_idx", columns={"subjectGroup"})})
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $username = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $password = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $email = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="forename", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $forename = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="surname", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $surname = 'NULL';

    /**
     * @var \Subjectgroup
     *
     * @ORM\ManyToOne(targetEntity="Subjectgroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subjectGroup", referencedColumnName="id")
     * })
     */
    private $subjectgroup;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Subject", inversedBy="teacher")
     * @ORM\JoinTable(name="subjecttecher",
     *   joinColumns={
     *     @ORM\JoinColumn(name="teacher", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="subject", referencedColumnName="id")
     *   }
     * )
     */
    private $subject;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subject = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getForename(): ?string
    {
        return $this->forename;
    }

    public function setForename(?string $forename): self
    {
        $this->forename = $forename;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getSubjectgroup(): ?Subjectgroup
    {
        return $this->subjectgroup;
    }

    public function setSubjectgroup(?Subjectgroup $subjectgroup): self
    {
        $this->subjectgroup = $subjectgroup;

        return $this;
    }

    /**
     * @return Collection|Subject[]
     */
    public function getSubject(): Collection
    {
        return $this->subject;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subject->contains($subject)) {
            $this->subject[] = $subject;
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subject->contains($subject)) {
            $this->subject->removeElement($subject);
        }

        return $this;
    }

}
