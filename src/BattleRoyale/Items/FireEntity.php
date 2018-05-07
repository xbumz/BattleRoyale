<?php

namespace BattleRoyale\Items;

use pocketmine\entity\Entity;
use pocketmine\Player;

class FireEntity extends CustomProjectile {

	const NETWORK_ID = 94;

	public function getName(){
		return "Fireball";
	}

	public function onCollideWithEntity(Entity $entity){
		if($entity instanceof Player){
			$entity->setOnFire(10);
		}
	}

	public function onUpdate($currentTick){
		parent::onUpdate($currentTick);
		if($this->hadCollision){
			$this->close();
		}
	}

}