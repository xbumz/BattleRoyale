<?php 

namespace BattleRoyale\Items;

use pocketmine\Player;
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;

class RoyalPotion extends RoyaleFood {

	public function __construct($meta = 0, $count = 1){
		parent::__construct(373, $meta, $count, "Potion");
	}

	public function onConsume(Entity $entity){
		if($entity instanceof Player){
			$potion = $this->getEffect();
			if(is_null($potion)){
				return;
			}
			if($entity->hasEffect($potion->getId())){
				$effect = $entity->getEffect($potion->getId());
				if($this->canReplaceAmplifier()){
					$effect->setAmplifier($potion->getAmplifier());
				}else{
					if($effect->getAmplifier() < $potion->getAmplifier()){
						$effect->setAmplifier($potion->getAmplifier());
					}
				}
				$effect->setDuration($effect->getDuration() + $potion->getDuration());
				$effect->setVisible($potion->isVisible());
				$entity->addEffect($effect);
			}else{
				$entity->addEffect($this->getEffect());
			}
			parent::onConsume($entity);
		}
	}

	public function canReplaceAmplifier(){
		return $this->getDamage() === 4 || $this->getDamage() === 14;
	}

	public function getEffect(){
		if($this->getDamage() === 4){
			$effect = Effect::getEffect(11)->setDuration(45*20)->setAmplifier(0)->setVisible(false);
			return $effect;
		}
		if($this->getDamage() === 14){
			$effect = Effect::getEffect(1)->setDuration(60*20)->setAmplifier(0)->setVisible(false);
			return $effect;
		}
	}

}