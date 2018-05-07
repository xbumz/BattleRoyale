<?php

namespace BattleRoyale\Utilities;

use BattleRoyale\GameManager;
use BattleRoyale\Game\Arena;
use BattleRoyale\Sessions\Playing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\utils\TextFormat;
use ZipArchive;

class Utils {

	public static function getPlayer(string $player){
		return array_key_exists($player, GameManager::$players) ? GameManager::$players[$player] : null;
	}

	public static function resetPlayer(Playing $session, $cause = false, $died = false, $win = false, $over = false){
		$player = $session->getPlayer();
		$session::$custom = $cause;
		$session->getArena()->removePlayer($player->getName());
		if($over){
			$player->sendMessage(TextFormat::RED."Se ha agotado el tiempo de la partida!");
			$session->setPosition(100);
		}
		if($died){
			$player->addTitle(TextFormat::YELLOW."Has muerto...", TextFormat::GRAY."posicion: #".$session->getPosition());
		}
		if($win){
			$player->sendMessage(TextFormat::YELLOW."Felicitaciones has ganado esta partida!");
			$player->addTitle(TextFormat::YELLOW."Has ganado!", TextFormat::GRAY."posicion: #".$session->getPosition());
		}
		$player->sendMessage(TextFormat::YELLOW."> Calculando tus stats por favor espera...");
		$player->sendMessage($message = str_repeat(TextFormat::GRAY."=", 36));
		foreach($session->calculateStats() as $key => $value){
			$player->sendMessage(TextFormat::YELLOW.$key.TextFormat::GRAY." >> ".TextFormat::WHITE.$value);
		}
		$player->sendMessage($message);
		unset($message);
		$session->deleteSession();
	}

	public static function unzipLevel(string $file){
		$zip = new ZipArchive;
		if(($plugin = GameManager::getInstance())->getServer()->isLevelLoaded($name = str_replace(".zip", "", $file))){
			$plugin->getServer()->unloadLevel($plugin->getServer()->getLevelByName($name));
		}
		if($zip->open($plugin->getDataFolder()."Maps/$file")){
			$zip->extractTo($plugin->getServer()->getDataPath()."worlds/$name");
			$plugin->getServer()->loadLevel($name);
		}else{
			$plugin->getLogger()->warning(TextFormat::RED."> Se ha producido un error al tratar de descomprimir este mundo!");
		}
		return $plugin->getServer()->getLevelByName($name);
	}

	public static function getVector(string $vector, $center = ":"){
		$values = explode($center, $vector);
		if(count($values) < 3){
			return new Vector3(0, 0, 0);
		}else{
			return new Vector3($values[0], $values[1], $values[2]); //x, y, z
		}
	}
	
	public static function getNBT(Vector3 $vector): CompoundTag{
		$data = new CompoundTag("", array());
		$data->Pos = new ListTag("Pos", array(
			new DoubleTag("", $vector->getX()), 
			new DoubleTag("", $vector->getY()), 
			new DoubleTag("", $vector->getZ()))
	);
		$data->Motion = new ListTag("Motion", array(
			new DoubleTag("", 0), 
			new DoubleTag("", 0), 
			new DoubleTag("", 0))
	);
		$data->Rotation = new ListTag("Rotation", array(
			new FloatTag("", 0.0), 
			new FloatTag("", 0.0))
	);
		return $data;
	}

	public static function addArena(array $data, string $arena, string $directory){
		if(count(array_values($data)) !== 7){
			GameManager::getInstance()->getLogger()->warning(TextFormat::RED."Faltan valores para la arena: ".$arena);
			return;
		}
		if(!is_file($directory.$data["level"].".zip")){
			GameManager::getInstance()->getLogger()->warning(TextFormat::RED."Falta el mapa para la arena: ".$arena);
				return;
		}
		GameManager::getInstance()->arenas[$arena] = new Arena($data, $arena);
		GameManager::getInstance()->getLogger()->info(TextFormat::GREEN."Se ha agregado una nueva arena: ".$arena);
	}

	public static function isCreating(string $player){
		return array_key_exists($player, GameManager::$creators) ? GameManager::$creators[$player] : null;
	}

}