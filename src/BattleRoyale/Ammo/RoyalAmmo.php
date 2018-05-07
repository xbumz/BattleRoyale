<?php

namespace BattleRoyale\Ammo;

use pocketmine\entity\Entity;
use pocketmine\entity\Snowball;
use pocketmine\level\particle\HappyVillagerParticle;

class RoyalAmmo extends Snowball {

  private $playerDamage = 0;

  public function onUpdate($currentTick){
    parent::onUpdate($currentTick);
    if(!$this->closed){
      $this->level->addParticle(new HappyVillagerParticle($this), $this->getViewers());//cambiar por fuego
    }
  }

  public function getDamageValue(): int{
    return $this->playerDamage;
  }

  public function setDamageValue(int $value){
    $this->playerDamage = $value;
  }

  public function canCollide(Entity $entity){
    return $entity !== $this->getOwningEntity();
  }

}
