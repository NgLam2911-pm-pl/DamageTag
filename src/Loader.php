<?php
declare(strict_types=1);

namespace DamageTag;

use DamageTag\entity\DamageTagEntity;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class Loader extends PluginBase implements Listener{

	public function onEnable() : void{
		EntityFactory::getInstance()->register(DamageTagEntity::class, function(World $world, CompoundTag $nbt) : DamageTagEntity{
			return new DamageTagEntity(EntityDataHelper::parseLocation($nbt, $world), 0, $nbt);
		}, ['DamageTag', 'minecraft:damagetag'], EntityLegacyIds::FALLING_BLOCK);

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @param EntityDamageEvent $event
	 * @priority MONITOR
	 * @handleCancelled FALSE
	 */
	public function onDamage(EntityDamageEvent $event){
		$entity = $event->getEntity();
		if (!$entity instanceof Living){
			return;
		}
		$damage = $event->getFinalDamage();
		$location = $entity->getLocation();
		$location->y += 1.5;
		$tag = new DamageTagEntity($location, $damage);
		$tag->spawnToAll();
	}
}