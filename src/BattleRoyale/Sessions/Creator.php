<?php

namespace BattleRoyale\Sessions;

use BattleRoyale\GameManager;
use BattleRoyale\Utilities\Utils;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Creator {

  public $arena;
  public $player;

  static $options = array("level" => null, "direction" => true, "max" => null, "lobby" => null, "center" => null, "radius" => null, "chests" => array());

  public function __construct(Player $player, string $arena){
    $this->arena = $arena;
    $this->player = $player;
  }

  public function getPlayer(): Player{
    return $this->player;
  }

  public function getChests(): array{
    return Creator::$options["chests"];
  }

  public function addChest(string $vector){
    Creator::$options["chests"][] = $vector; 
  }

  public function removeChest(string $vector){
    if(in_array($vector, Creator::$options["chests"])){
      unset(Creator::$options["chests"][array_search($vector, Creator::$options["chests"])]);
    }
  }

  public function setLevel(string $levelname){
    Creator::$options["level"] = $levelname;
  }

  public function setRadius(int $value){
    Creator::$options["radius"] = $value;
  }

  public function setCenter(string $center){
    Creator::$options["center"] = $center;
  }

  public function setMax(int $amount){
    Creator::$options["max"] = $amount;
  }

  public function setLobby(string $lobby){
    Creator::$options["lobby"] = $lobby;
  }

  public function setStart(string $start){
    Creator::$options["start"] = $start;
  }

  public function finishArena(){
    foreach(Creator::$options as $key => $value){
      if(is_null($value)){
        break;
        $this->getPlayer()->sendMessage(TextFormat::RED."Necesitas llenar todos los requisitos, valor faltante: ".TextFormat::YELLOW.$key);
        return;
      }
    }
    $path = ($plugin = GameManager::getInstance())->getServer()->getDataPath();
    $zip = new ZipArchive;
    $zip->open($plugin->getDataFolder()."Maps/".Creator::$options["level"].".zip", ZipArchive::CREATE);
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path."worlds/".Creator::$options["level"]));
    foreach($files as $file){
      if(is_file($file)){
        $zip->addFile($file, str_replace("\\", "/", ltrim(substr($file, strlen($path."worlds/".Creator::$options["level"])), "/\\")));
      }
    }
    $zip->close();
    $config = new Config($plugin->getDataFolder()."Games/".$this->getArena().".yml", Config::YAML, []);
    foreach(Creator::$options as $key => $value){
      $config->set($key, $value);
      $config->save();
    }
    unset(GameManager::getInstance()::$creators[$this->getPlayer()->getName()]);
    Utils::addArena($config->getAll(), $this->getArena(), $plugin->getDataFolder()."Maps/");
  }

  public function getArena(): string{
    return $this->arena;
  }

}
