<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    public function __construct(bool $turn, bool $winner)
    {
        $this->turn = $turn;
        $this->winner = $winner;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $turn;

    /**
     * @ORM\Column(type="boolean")
     */
    private $winner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTurn(): ?bool
    {
        return $this->turn;
    }

    public function setTurn(bool $turn): self
    {
        $this->turn = $turn;

        return $this;
    }

    public function getText(bool $next = false): ?string
    {
        if ($next)
            return $this->turn ? 'O' : 'X';
        else 
            return $this->turn ? 'X' : 'O';
    }

    public function getWinner(): ?bool
    {
        return $this->winner;
    }

    public function setWinner(bool $winner): self
    {
        $this->winner = $winner;

        return $this;
    }
}
