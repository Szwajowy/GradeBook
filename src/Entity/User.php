<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="fk_User_Group1_idx", columns={"subjectGroup"})})
 * @ORM\Entity
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=45, unique=true, nullable=true, options={"default"="NULL"})
     */
    private $username;

    /**
     * @var string
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", length=255, nullable=false)
     */
    private $roles = [];

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=45, unique=true, nullable=true, options={"default"="NULL"})
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="forename", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $forename;

    /**
     * @var string|null
     *
     * @ORM\Column(name="surname", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $surname;

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

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
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

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
    
        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }

    public function resetRoles()
    {
        $this->roles = [];
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

    public function getSalt() {
        
    }

    public function eraseCredentials() {
    }

    public function serialize() {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->roles
        ]);
    }

    public function unserialize($string) {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->roles
        ) = unserialize($string, ['allowed_classes' => false]);
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }
}
