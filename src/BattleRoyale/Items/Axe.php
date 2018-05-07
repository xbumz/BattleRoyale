<?php 

namespace BattleRoyale\Items;

use pocketmine\item\StoneAxe;

class Axe extends StoneAxe {

	public function useOn($object){
		return true;
	}
	
}


