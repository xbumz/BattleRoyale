<?php  

namespace BattleRoyale\AirDrop;

use pocketmine\Player;
use pocketmine\tile\Tile;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\CompoundTag;

class AirDropManager {

	public static function addAirDrop(Player $player, array $contents, int $id){
		$chest = Tile::createTile("BoxChest", $player->getLevel(), new CompoundTag("", array(new IntTag("x", $player->getX()), new IntTag("y", $player->getY() - 3), new IntTag("z", $player->getZ()), new StringTag("Id", Tile::CHEST), new StringTag("CustomName", "> Air Drop Menu <"), new IntTag("Entity", $id))));
		$block = Block::get(54, 0);
		$block->x = $chest->x;
		$block->y = $chest->y;
		$block->z = $chest->z;
		$block->level = $player->getLevel();
		$player->getLevel()->sendBlocks(array($player), array($block));
		$chest->spawnTo($player);
		$chest->setNewInventory($contents);
		$player->addWindow($chest->getInventory());
		$chest->getInventory()->setContents($contents);
		
	}

	public static function updateInventory(int $id, array $contents, Level $level){
		if(!is_null($entity = $level->getEntity($id))){
			$entity->setInventory($contents);
		}
	}

}