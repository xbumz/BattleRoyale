<?php

namespace BattleRoyale\Guns;

class IronGun extends GunClass {

  static $zoom = false;
  static $max = 20;
  static $ammo = 0;
  static $damage = 3.9;
  static $reload = 7;
  static $reloading = false;
  static $scheduler = 0;

  public function __construct($meta = 0, $count = 1){
    parent::__construct(417, $meta, $count, "M416");
  }

}