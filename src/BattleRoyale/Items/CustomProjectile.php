<?php  

namespace BattleRoyale\Items;

use pocketmine\Player;
use pocketmine\entity\Projectile;
use pocketmine\network\mcpe\protocol\AddEntityPacket;

abstract class CustomProjectile extends Projectile {

	public $gravity = 0.1;
	public $drag = 0.25;
	public $height = 0.25;
	public $weight = 0.25;
	public $lenght = 0.25;

	public function initEntity(){
		$this->setNameTag($this->getName());
  	    $this->setNameTagVisible(true);
  	    $this->setNameTagAlwaysVisible(true);
		parent::initEntity();
	}

	public function saveNBT(){
		parent::saveNBT();
	}

	public function spawnTo(Player $player){
		$packet = new AddEntityPacket();
		$packet->type = static::NETWORK_ID;
		$packet->speedX = $this->motionX;
		$packet->speedY = $this->motionY;
		$packet->speedZ = $this->motionZ;
		$packet->x = $this->getX();
		$packet->y = $this->getY();
		$packet->z = $this->getZ();
		$packet->entityRuntimeId = $this->getId();
		$packet->pitch = $this->pitch;
		$packet->yaw = $this->yaw;
		$packet->metadata = $this->dataProperties;
		$player->dataPacket($packet);
		parent::spawnTo($player);
	}

}