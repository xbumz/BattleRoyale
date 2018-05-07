<?php

namespace BattleRoyale\Items;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\math\Vector3;

class Launcher extends Item {

  public function __construct($meta = 0, $count = 1){
    parent::__construct(288, $meta, $count, "Launcher");
  }
  
  public function launchPlayer(Player $player, bool $falling){
    if(!$falling){
      return;
    }
  	$direction = $player->getDirectionVector();
  	$player->knockBack($player, 0, $direction->getX(), $direction->getZ(), 1.2);
    if(!$player->getDataFlag($player::DATA_FLAGS, $player::DATA_FLAG_GLIDING)){
      $player->setDataFlag($player::DATA_FLAGS, $player::DATA_FLAG_GLIDING, true);
    }
  	$this->count--;
  	$player->getInventory()->setItemInHand($this->count > 0 ? clone $this : Item::get(Item::AIR, 0));
  }

}
