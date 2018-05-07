<?php

namespace BattleRoyale\Guns;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\entity\Entity;
use BattleRoyale\EntityManager;

abstract class GunClass extends Item {

  static protected $max;
  static protected $zoom;
  static protected $ammo; // TODO: usar de otra manera xD
  static protected $damage;
  static protected $reload;
  static protected $reloading;
  static protected $scheduler;
  
  public function getAmmo(): int{
    return static::$ammo;
  }

  public function canZoom(): bool{
    return static::$zoom;
  }

  public function setAmmo(int $value){
    static::$ammo = $value;
  }

  public function getReloadTime(): int{
    return static::$reload;
  }

  public function isReloading(): bool{
    return static::$reloading;
  }

  public function setReloading(bool $value){
    static::$reloading = $value;
  }

  public function getMax(): int{
    return static::$max;
  }

  public function getCurrentTime(): int{
    return round(static::$scheduler, 3);
  }

  public function setCurrentTime($value){
    static::$scheduler = $value;
  }

  public function getDamageValue(): int{
    return static::$damage;
  }

  public function hasAmmo(Player $player): int{
    $has = null;
    foreach($player->getInventory()->getContents() as $key => $item){
      if($item->getId() === 144 || $item->getId() === 397){
        $has = $item;
        break;
      }
    }
    if(!is_null($has)){
      $count = $has->getCount();
      if($count >= $this->getMax()){
        $count = $this->getMax();
      }else if($count > 0){
        $count = $this->getMax()-(abs($this->getMax()-$count));
      }else{
        $count = 0;
      }
      $player->getInventory()->removeItem(Item::get($has->getId(), $has->getDamage(), $count));
      return $count;
    }else{
      return 0;
    }
  }

  public function checkStatus(Player $player){
    if($this->isReloading()){
      $end = microtime(true);
      if(($time = round($end-$this->getCurrentTime(), 3)) >= $this->getReloadTime()){
        $this->setReloading(false);
        $this->setCurrentTime(0);
        return true;
      }else{
        $player->sendPopup(TextFormat::GOLD."Tu arma se esta recargando, ".TextFormat::GREEN.$time.TextFormat::RED." segundos restantes...");
        return false;
      }
    }else{
      if($this->getAmmo() <= 0){
        if(($ammo = $this->hasAmmo($player)) <= 0){
          $player->sendPopup(TextFormat::RED."No tienes municion, ".TextFormat::WHITE."$ammo");
          return false;
        }else{
          $this->setReloading(true);
          $this->setCurrentTime(microtime(true));
          $this->setAmmo($ammo);
          $this->checkStatus($player);
        }
      }else{
        return true;
      }
    }
  }

  public function getMaxStackSize(){
    return 1;
  }

  public function shootClass(Item $item, Player $player){
    $motion = 0.0;
    switch($item->getId()){
      case 416:
      $motion = 1.8;
      break;
      case 417:
      $motion = 2.1;
      break;
      case 418:
      $motion = 2.5;
      break;
      case 419:
      $motion = 3.5;
      break;
    }
    $bullet = Entity::createEntity("RoyalAmmo", $player->getLevel(), EntityManager::getCompoundMotion($player), $player);
    $bullet->setMotion($bullet->getMotion()->multiply($motion));
    $bullet->setDamageValue($this->getDamageValue());
    $bullet->spawnToAll();
    $player->getLevel()->addSound(new BlazeShootSound($player), array_merge(array($player), $player->getViewers()));
    $this->setAmmo($this->getAmmo() - 1);
    if($this->getAmmo() < 1){
      $this->checkStatus($player);
    }
  }

  public function useGun(Player $player){
    if($this->checkStatus($player)){
      $this->shootClass($player->getInventory()->getItemInHand(), $player);
      $player->setXpLevel($this->getAmmo());
    }
  }

}