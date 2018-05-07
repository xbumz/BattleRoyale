<?php  

namespace BattleRoyale\AirDrop;

use pocketmine\Player;
use BattleRoyale\Utilities\Utils;
use BattleRoyale\Game\ChestItems;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use BattleRoyale\EntityManager;

class BoxEntity extends EntityManager {

  const NETWORK_ID = 71;
  
  public $height = 1;
  public $lenght = 1;
  public $weight = 1;
  
  private $contents = array();

  public function initEntity(){
  	$this->setNameTag($this->getName());
  	$this->setNameTagVisible(true);
  	$this->setNameTagAlwaysVisible(true);
    ChestItems::fillAirDrop($this);
    parent::initEntity();
  }

  public function setInventory(array $contents){
  	$this->contents = $contents;
  }

  public function getContents(): array{
  	return $this->contents;
  }

  public function getName(){
    return ">> Air Drop <<";
  }

  public function attack($damage, EntityDamageEvent $source){
    parent::attack($damage, $source);
    if($source instanceof EntityDamageByEntityEvent){
    	$player = $source->getDamager();
    	if($player instanceof Player){
    		if(!is_null(Utils::getPlayer($player->getName()))){
    			AirDropManager::addAirDrop($player, $this->getContents(), $this->getId());
    		}
    	}
    }
  }
  
}