<?php  

namespace BattleRoyale\Items;

use pocketmine\block\Block;

class EggLauncher extends CustomProjectile {

	const NETWORK_ID = 82;

	public function getName(){
		return "Constructor";
	}

	public function build(){
		for($x = 0; $x < 2; ++$x){
			for($z = 2; $z > 0; --$z){
				$this->level->setBlock($this->add($x, 0, $z), Block::get(Block::SANDSTONE, 0));
			}
		}
		//$this->level->setBlock($this, Block::get(Block::SANDSTONE, 0));
	}

	public function onUpdate($tick){
		parent::onUpdate($tick);
		if(!$this->hadCollision){
			//if($this->ticksLived % 3 === 0){
				$this->build();
			//}
		}else{
			if($this->isAlive()){
				$this->close();
			}
		}
	}

}