<?php

namespace BattleRoyale\Guns;

class GoldenGun extends GunClass {

  static $zoom = false;
  static $max = 25;
  static $ammo = 0;
  static $damage = 3.7;
  static $reload = 3;
  static $reloading = false;
  static $scheduler = 0;

  public function __construct($meta = 0, $count = 1){
    parent::__construct(418, $meta, $count, "SMG Submachine");
  }

}