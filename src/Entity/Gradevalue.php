<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gradevalue
 *
 * @ORM\Table(name="gradevalue")
 * @ORM\Entity
 */
class Gradevalue
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
     * @var int
     *
     * @ORM\Column(name="content", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?int
    {
        return $this->content;
    }

    public function setContent(int $content): self
    {
        $this->content = $content;

        return $this;
    }


}
