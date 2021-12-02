<?php
declare(strict_types=1);

namespace DamageTag\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class DamageTagEntity extends Entity{

	protected float $damage = 0;

	protected $gravity = 0;
	protected $gravityEnabled = false;
	public $canCollide = false;
	public $keepMovement = true;
	protected $drag = 0.0;
	protected $scale = 0.0;
	protected $immobile = true;

	public static function getNetworkTypeId() : string{
		return EntityIds::FALLING_BLOCK;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.0, 0.0);
	}

	public function __construct(Location $location, float $damage = 0, ?CompoundTag $nbt = null){
		$this->damage = $damage;
		parent::__construct($location, $nbt);
	}

	public function initEntity(CompoundTag $nbt) : void{
		parent::initEntity($nbt);
		$this->setNameTag((string)$this->damage);
		$this->setNameTagAlwaysVisible(true);
	}

	public function onUpdate(int $currentTick) : bool{
		if ($this->ticksLived > 30){ //1.5 seconds
			$this->flagForDespawn();
			return true;
		}
		return parent::onUpdate($currentTick);
	}

	public function attack(EntityDamageEvent $source) : void{
		$source->cancel();
	}

	public function canCollideWith(Entity $entity) : bool{
		return false;
	}

	public function canBeCollidedWith() : bool{
		return false;
	}

	public function canBeMovedByCurrents() : bool{
		return false;
	}

	public function isFireProof() : bool{
		return true;
	}

	public function getOffsetPosition(Vector3 $vector3) : Vector3{
		return parent::getOffsetPosition($vector3)->add(0.0, 0.49, 0.0);
	}


}