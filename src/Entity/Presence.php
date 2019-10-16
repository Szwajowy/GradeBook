<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Presence
 *
 * @ORM\Table(name="presence", indexes={@ORM\Index(name="fk_Presence_User1_idx", columns={"student"}), @ORM\Index(name="fk_Presence_PresenceType1_idx", columns={"presenceValue"}), @ORM\Index(name="fk_Presence_ClassBlock1_idx", columns={"classBlock"})})
 * @ORM\Entity
 */
class Presence
{
    /**
     * @var \Classblock
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Classblock")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="classBlock", referencedColumnName="id")
     * })
     */
    private $classblock;

    /**
     * @var \Presencevalue
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Presencevalue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="presenceValue", referencedColumnName="id")
     * })
     */
    private $presencevalue;

    /**
     * @var \User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student", referencedColumnName="id")
     * })
     */
    private $student;

    public function getClassblock(): ?Classblock
    {
        return $this->classblock;
    }

    public function setClassblock(?Classblock $classblock): self
    {
        $this->classblock = $classblock;

        return $this;
    }

    public function getPresencevalue(): ?Presencevalue
    {
        return $this->presencevalue;
    }

    public function setPresencevalue(?Presencevalue $presencevalue): self
    {
        $this->presencevalue = $presencevalue;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): self
    {
        $this->student = $student;

        return $this;
    }


}
