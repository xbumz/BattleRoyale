<?php

namespace BattleRoyale;

use pocketmine\Player;
use pocketmine\entity\Living;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;

abstract class EntityManager extends Living {

	public function initEntity(){
		parent::initEntity();
	}

	public function saveNBT(){
		parent::saveNBT();
	}

	public function attack($damage, EntityDamageEvent $source){
		if($source->getCause() !== EntityDamageEvent::CAUSE_VOID){
			$source->setCancelled();
		}else{
			if($this->isAlive()){
				$this->close();
			}
		}
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

	public static function getCompoundMotion(Player $player): CompoundTag{
		$data = new CompoundTag("", []);
		$data->Pos = new ListTag(
			"Pos", array(
				new DoubleTag("", $player->getX()), 
				new DoubleTag("", $player->getY() + $player->getEyeHeight()), 
				new DoubleTag("", $player->getZ())));
		$data->Motion = new ListTag(
			"Motion", array(
				new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)), 
				new DoubleTag("", -sin($player->pitch / 180 * M_PI)), 
				new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))));
		$data->Rotation = new ListTag(
			"Rotation", array(
				new FloatTag("", $player->yaw), 
				new FloatTag("", $player->pitch)));
		return $data;
	}
}