<?php

namespace BattleRoyale\Items;

use pocketmine\item\Item;
use pocketmine\player;
use pocketmine\entity\Entity;
use BattleRoyale\EntityManager;

class Fireball extends Item {

  public function __construct($meta = 0, $count = 1){
    parent::__construct(385, $meta, $count, "Fireball");
  }

  public function shootBall(Player $player){
  	$fireball = Entity::createEntity("FireEntity", $player->getLevel(), EntityManager::getCompoundMotion($player), $player);
  	$fireball->setMotion($fireball->getMotion()->multiply(2.3));
  	$fireball->spawnToAll();
  	$this->count--;
  	$player->getInventory()->setItemInHand($this->count > 0 ? clone $this : Item::get(Item::AIR, 0));
  }

}
