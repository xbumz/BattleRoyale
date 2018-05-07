<?php

namespace BattleRoyale\Items;

use pocketmine\entity\Entity;
use pocketmine\entity\Effect;

class Bandage extends RoyaleFood {

  public function __construct($meta = 0, $count = 1){
    parent::__construct(322, $meta, $count, "Bandage");
  }

  public function onConsume(Entity $player){
    if($player->getHealth() >= $player->getMaxHealth()){
      $player->sendPopup("Tu salud esta al maximo!");
    }else{
      if($player->hasEffect(Effect::REGENERATION)){
        $effect = $player->getEffect(Effect::REGENERATION);
        $effect->setDuration($effect->getDuration() + $this->getEffect()->getDuration());
        $player->addEffect($effect);
      }else{
        $player->addEffect($this->getEffect());
      }
      parent::onConsume($player);
    }
  }

  public function getEffect(): Effect{
    $effect = Effect::getEffect(Effect::REGENERATION)->setAmplifier(3)->setVisible(false);
    if($this->getDamage() === 0){
      $effect->setDuration(1 * 20);
    }else{
      $effect->setDuration(3 * 20);
    }
    return $effect;
  }

}
