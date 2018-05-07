<?php

namespace BattleRoyale\Items;

use pocketmine\item\StonePickaxe;

class Pickaxe extends StonePickaxe {

	public function useOn($object){
		return true;
	}
	
}
