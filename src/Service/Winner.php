<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Square;

class Winner
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function calculateWinner(array $squares): bool {
        $lines = array();
        $lines[] = array(0, 1, 2);
        $lines[] = array(3, 4, 5);
        $lines[] = array(6, 7, 8);
        $lines[] = array(0, 3, 6);
        $lines[] = array(1, 4, 7);
        $lines[] = array(2, 5, 8);
        $lines[] = array(0, 4, 8);
        $lines[] = array(2, 4, 6);

        $count = count($lines); 

        for ($i = 0; $i < $count; $i++) {
            $posible = $lines[$i];
            if ($squares[$posible[0]] && $squares[$posible[0]] === $squares[$posible[1]] && $squares[$posible[0]] === $squares[$posible[2]]) {
                return $squares[$posible[0]];
            }
        }
        
        return false;
    }

    public function convertToString(array $current): string {
        $current = array_map( function($val) {
            if ( is_null($val) )
                return '-';
            else
                return $val;
        }, $current);

        return implode(',', $current);
    }

    public function convertToArray(string $last): array {
        $current = explode(',', $last);
        return array_map( function($val) {
             if ( $val == '-' )
                return null;
            else
                return $val;
        }, $current);
    }

    public function resetGame(){        
        $repository = $this->em->getRepository(Square::class);

        $list = $repository->findOtherSquare('-,-,-,-,-,-,-,-,-');

        foreach ($list as $key => $value) {
            $this->em->remove($value);
        }

        $this->em->flush();
    }
}