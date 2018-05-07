<?php

namespace BattleRoyale\Guns;

class DiamondGun extends GunClass {

  static $zoom = true;
  static $max = 15;
  static $ammo = 0;
  static $damage = 4.5;
  static $reload = 10;
  static $reloading = false;
  static $scheduler = 0;

  public function __construct($meta = 0, $count = 1){
    parent::__construct(419, $meta, $count, "Rifle");
  }

}