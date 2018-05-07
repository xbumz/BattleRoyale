<?php

namespace BattleRoyale\Guns;

class LeatherGun extends GunClass {

  static $zoom = false;
  static $max = 10;
  static $ammo = 0;
  static $damage = 3.0;
  static $reload = 5;
  static $reloading = false;
  static $scheduler = 0;

  public function __construct($meta = 0, $count = 1){
    parent::__construct(416, $meta, $count, "Pistol");
  }

}
