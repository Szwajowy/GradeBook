<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject", indexes={@ORM\Index(name="fk_Subject_SubjectGroup1_idx", columns={"subjectGroup"}), @ORM\Index(name="fk_Subject_SubjectName1_idx", columns={"subjectName"})})
 * @ORM\Entity
 */
class Subject
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
     * @var \Subjectgroup
     *
     * @ORM\ManyToOne(targetEntity="Subjectgroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subjectGroup", referencedColumnName="id")
     * })
     */
    private $subjectgroup;

    /**
     * @var \Subjectname
     *
     * @ORM\ManyToOne(targetEntity="Subjectname")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subjectName", referencedColumnName="id")
     * })
     */
    private $subjectname;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="subject")
     */
    private $teacher;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teacher = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSubjectname(): ?Subjectname
    {
        return $this->subjectname;
    }

    public function setSubjectname(?Subjectname $subjectname): self
    {
        $this->subjectname = $subjectname;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getTeacher(): Collection
    {
        return $this->teacher;
    }

    public function addTeacher(User $teacher): self
    {
        if (!$this->teacher->contains($teacher)) {
            $this->teacher[] = $teacher;
            $teacher->addSubject($this);
        }

        return $this;
    }

    public function removeTeacher(User $teacher): self
    {
        if ($this->teacher->contains($teacher)) {
            $this->teacher->removeElement($teacher);
            $teacher->removeSubject($this);
        }

        return $this;
    }

}
