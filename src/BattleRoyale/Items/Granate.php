<?php

namespace BattleRoyale\Items;

use pocketmine\item\Item;
use pocketmine\player;
use pocketmine\entity\Entity;
use BattleRoyale\EntityManager;

class Granate extends Item {

  public function __construct($meta = 0, $count = 1){
    parent::__construct(384, $meta, $count, "Granada");
  }

  public function throwGranate(Player $player){
  	$granate = Entity::createEntity("GranateEntity", $player->getLevel(), EntityManager::getCompoundMotion($player), $player);
  	if($this->getDamage() !== 0){
  		$granate->setType(1);
  	}
  	$granate->setMotion($granate->getMotion()->multiply(1.5));
  	$granate->spawnToAll();
  	$this->count--;
  	$player->getInventory()->setItemInHand($this->count > 0 ? clone $this : Item::get(Item::AIR, 0));
  }

}
