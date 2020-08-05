<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Winner;
use App\Entity\Player;
use App\Entity\Square;

class DefaultController extends AbstractController
{
	private $winner;    

    public function __construct(Winner $winner)
    {
        $this->winner = $winner;
    }

    /**
     *	Part 1: create player if does'not exist
     *  Part 2: initilize historial
     *
     * @Route("/", name="default")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Player::class);
        $lists = $repository->findAll();
        $turn = true;
        
        if (count($lists) == 0) {
            $player = new Player($turn, false);
            $em->persist($player);            
        } else {
            $player = $lists[0];
            $turn = $player->getTurn();
            $player->setWinner(false);
        }        

        $repository = $em->getRepository(Square::class);
        $lists = $repository->findAll();

        if (count($lists) == 0) {
            $history = new Square();
            $history->setValue('-,-,-,-,-,-,-,-,-');
            $em->persist($history);
        } else {
            $this->winner->resetGame();
        }

        $em->flush();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'turn' => $turn ? 'Next player: X' : 'Next player: O'
        ]);
    }

	/**
	 * Part 1: Check if exists a player
     * Part 2: Check if exists a winner
     * Part 3: Check if i'm clicking a occupied cell
     * Part 4: Play game and check if a exists a winner or not
	 *
     * @Route("/play/{component}/{cell}", name="select_cell")
     */
    public function playGame(string $component, string $cell) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Player::class);

        try {        
            $player = $repository->findOneBy(['turn' => true ]);
            
            if (is_null($player)) {
                $player = $repository->findOneBy(['turn' => false ]);
            }

            if (is_null($player))
                return $this->json(array( 'error' => 'no player found' ), $status = 500, $headers = [], $context = []);

            // there is a winner
            if ($repository->findOneBy(['winner' => true ])) {
                return $this->json(array( 
                    'component' => $component, 
                    'played' => $player->getText(true), 
                    'player' => $player->getText(true),
                    'winner' => true
                ), $status = 200, $headers = [], $context = []);
            }

            $history = $em->getRepository(Square::class)->findBy(array(), array('id' => 'DESC'));
            $last = $history[0]->getValue();
            $current = $this->winner->convertToArray($last); 
        
            // cell already occupied
            if ($current[intval($cell)]){
                return $this->json(array( 
                    'component' => $component, 
                    'played' => $player->getText(true), 
                    'player' => $player->getText(true),
                    'winner' => false
                ), $status = 200, $headers = [], $context = []);
            }

            $winner = false;
            $current[intval($cell)] = $player->getText();
            $player->setTurn(!$player->getTurn());

            if ($this->winner->calculateWinner($current)) {
                $winner = true;
                $player->setWinner(true);              
            } else {
                $current = $this->winner->convertToString($current);                                                
                $history = new Square();
                $history->setValue($current);
                $em->persist($history);
            }

            $em->flush();

            return $this->json(array( 
                    'component' => $component, 
                    'played' => $player->getText(true), 
                    'player' => $player->getText(),
                    'winner' => $winner
                ), $status = 200, $headers = [], $context = []);

        } catch(\Exception $e) {
            return $this->json(array( 'error' => 'unexpected error'), $status = 500, $headers = [], $context = []);
        }        
    }
}
