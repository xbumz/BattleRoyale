<?php

namespace BattleRoyale\Timer;

use BattleRoyale\GameManager;
use pocketmine\scheduler\PluginTask;

class BattleTask extends PluginTask {

	public function __construct(GameManager $plugin){
		parent::__construct($plugin);
	}

	public function onRun($tick){
		if(empty($this->getOwner()->arenas)){
			return;
		}
		foreach($this->getOwner()->arenas as $name => $class){
			$class->runGame();
		}
	}

}
