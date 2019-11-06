<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grade
 *
 * @ORM\Table(name="grade", indexes={@ORM\Index(name="fk_Grade_GradeType1_idx", columns={"gradeType"}), @ORM\Index(name="fk_Grade_User2_idx", columns={"teacher"}), @ORM\Index(name="fk_Grade_Subject1_idx", columns={"subject"}), @ORM\Index(name="fk_Grade_User1_idx", columns={"student"}), @ORM\Index(name="fk_Grade_GradeValue1_idx", columns={"gradeValue"})})
 * @ORM\Entity(repositoryClass="App\Repository\GradeRepository")
 */
class Grade
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
     * @var \Gradetype
     *
     * @ORM\ManyToOne(targetEntity="Gradetype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gradeType", referencedColumnName="id")
     * })
     */
    private $gradetype;

    /**
     * @var \Gradevalue
     *
     * @ORM\ManyToOne(targetEntity="Gradevalue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gradeValue", referencedColumnName="id")
     * })
     */
    private $gradevalue;

    /**
     * @var \Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subject", referencedColumnName="id")
     * })
     */
    private $subject;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student", referencedColumnName="id")
     * })
     */
    private $student;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher", referencedColumnName="id")
     * })
     */
    private $teacher;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGradetype(): ?Gradetype
    {
        return $this->gradetype;
    }

    public function setGradetype(?Gradetype $gradetype): self
    {
        $this->gradetype = $gradetype;

        return $this;
    }

    public function getGradevalue(): ?Gradevalue
    {
        return $this->gradevalue;
    }

    public function setGradevalue(?Gradevalue $gradevalue): self
    {
        $this->gradevalue = $gradevalue;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

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

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(?User $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }


}
