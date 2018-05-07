<?php

namespace BattleRoyale\BossBar;

use pocketmine\entity\Attribute;

class BossSettings extends Attribute {

  public function __construct(){
    //Hola xD
  }

  public function getMinValue(){
    return 1;
  }

  public function getMaxValue(){
    return 600;
  }

  public function getValue(){
    return $this->getMaxValue();
  }

  public function getName(){
    return "minecraft:health";
  }

  public function getDefaultValue(){
    return $this->getValue();
  }

}
