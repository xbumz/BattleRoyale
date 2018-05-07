<?php

namespace BattleRoyale\Zone;

use BattleRoyale\Utilities\Utils;
use BattleRoyale\Game\Arena;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\entity\Entity;

class Storm {

	private $arena;
	public $vector;
	private $level;
	public $zone;
	public $storm;
	public $status;

	const CLOSING = 0;
	const STABLE = 1;

	public function __construct(Arena $game){
		$this->status = Storm::STABLE;
		$this->arena = $game;
		$this->vector = $game->getVector();
		$this->level = $game->getLevel();
	}

	public function findZone(){
		$this->zone = $this->getArena()->getRadius();
		$this->storm = $this->getCurrentZone();
		$vector = $this->getVector3();
		$x = $vector->getX() + mt_rand(-30, 30);
		$z = $vector->getZ() + mt_rand(-30, 30);
		$y = 128;
		while($y > 1){
			if($this->getLevel()->getBlockIdAt($x, $y, $z) === 0){
				$y--;
			}else{
				break;
			}
		}
		$this->getVector3()->setComponents($x, $y, $z);
	}

	public function calculateDistance(Player $player): int{
		return $this->getStorm() - round($this->isInside($player, true));
	}

	public function getArena(): Arena{
		return $this->arena;
	}

	public function setStatus(int $value){
		$this->status = $value;
	}

	public function getConvertedStatus(): string{
		if($this->getStatus() === Storm::STABLE){
			return TextFormat::GREEN."ESTABLE";
		}else{
			return TextFormat::RED."CERRANDO";
		}
	}

	public function getStatus(): int{
		return $this->status;
	}

	public function getVector3(): Vector3{
		return $this->vector;
	}

	public function getLevel(): Level{
		return $this->level;
	}

	public function getStorm(): int{
		return $this->storm;
	}

	public function getCurrentZone(): int{
		return $this->zone;
	}

	public function getVector33(): Vector3{
		$vector = $this->getVector3();
		$x = $vector->getX() + ($vector->getX() < 0 ? -$this->getStorm() : $this->getStorm());
		$z = $vector->getZ() + ($vector->getZ() < 0 ? -$this->getStorm() : $this->getStorm());
		return new Vector3($x, $vector->getY(), $z);
	}

	public function removeZone(): bool{
		if($this->zone > 25){
			$position = $this->getCurrentZone() - 30;
			if($position > 25){
				$this->zone = $position;
			}else{
				$this->zone = 24;
			}
			return true;
		}else{
			return false;
		}
	}

	public function spawnAirDrop(){
		$vector = $this->getVector3();
		$x = mt_rand(min($vector->getX(), $this->getCurrentZone()), max($vector->getX(), $this->getCurrentZone()));
		$z = mt_rand(min($vector->getZ(), $this->getCurrentZone()), max($vector->getZ(), $this->getCurrentZone()));
		$y = 128;
		while($y > 1){
			if($this->getLevel()->getBlockIdAt($x, $y, $z) === 0){
				$y--;
			}else{
				break;
			}
		}
		$airdrop = Entity::createEntity("BoxEntity", $this->getLevel(), Utils::getNBT(new Vector3($x, $y, $z)));
		$airdrop->spawnToAll();
	}

	public function isInside(Player $player, $return = false){
		if($return){
			return $player->distance($this->getVector3());
		}else{
			return $player->distance($this->getVector3()) <= $this->getStorm();
		}
		/*
		$times = 0;
		$vector = $this->getVector33();
		$vectorx = abs($vector->getX());
		$vectorz = abs($vector->getZ());
		if(abs($x) <= $vectorx || -$x >= $vectorx){
			$times++;
		}
		if(abs($z) <= $vectorz || -$z >= $vectorz){
			$times++;
		}
		return $times > 1;
		*/
	}

	public function updateZone(){
		if($this->getStorm() > $this->getCurrentZone()){
			$this->storm -= 1;
			if($this->getStatus() === Storm::STABLE){
				$this->setStatus(Storm::CLOSING);
			}
		}else{
			if($this->getStatus() === Storm::CLOSING){
				$this->setStatus(Storm::STABLE);
			}
		}
	}

}
