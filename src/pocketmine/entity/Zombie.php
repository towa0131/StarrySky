<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\entity;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item as ItemItem;
use pocketmine\math\Vector3;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\level\format\Chunk;
use pocketmine\Player;
use pocketmine\Server;

class Zombie extends Monster{
	const NETWORK_ID = 32;

	public $width = 0.6;
	public $length = 0.6;
	public $height = 1.8;

	public $walkDirection = null;
	public $walkspeed = 1.0;

	private $switchDirectionTicker = 0;

	public function initEntity(){
		parent::initEntity();
		$this->setMaxHealth(20);
	}

	public function getName(){
		return "Zombie";
	}

	public function attack($damage, EntityDamageEvent $source){
		parent::attack($damage, $source);
		if($source->isCancelled()){
			return;
		}
		if($source instanceof EntityDamageByEntityEvent){
			$this->walkspeed = mt_rand(150, 350) / 2000;
			$e = $source->getDamager();
			$this->walkDirection = (new Vector3($this->x - $e->x, $this->y - $e->y, $this->z - $e->z))->normalize();

			$pk = new EntityEventPacket();
			$pk->eid = $this->getId();
			$pk->event = EntityEventPacket::HURT_ANIMATION;
			Server::broadcastPacket($this->hasSpawned, $pk);
		}
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Zombie::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}

	private function generateRandomDirection(){
		return new Vector3(mt_rand(-1000, 1000) / 1000, mt_rand(-500, 500) / 1000, mt_rand(-1000, 1000) / 1000);
	}

	public function onUpdate($currentTick){
		if($this->closed !== false){
			return false;
		}

		if(++$this->switchDirectionTicker === 100){
			$this->switchDirectionTicker = 0;
			if(mt_rand(0, 100) < 50){
				$this->walkDirection = null;
			}
		}

		$this->lastUpdate = $currentTick;

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		if($this->isAlive()){
				$this->walkDirection = $this->generateRandomDirection();
				$this->burn($this);
					if($this->walkDirection != null){
					$this->motionX = $this->walkDirection->x * $this->walkspeed;
					$this->motionY = $this->walkDirection->y - 1.2;
					$this->motionZ = $this->walkDirection->z * $this->walkspeed;
			}
					$this->motionY -= $this->gravity;
			$expectedPos = new Vector3($this->x + $this->motionX, $this->y + $this->motionY, $this->z + $this->motionZ);

			$this->move($this->motionX, $this->motionY, $this->motionZ);

			if($expectedPos->distanceSquared($this) > 0){
				$this->walkDirection = $this->generateRandomDirection();
				$this->walkspeed = mt_rand(50, 100) / 1000;
			}


		}

		$this->timings->stopTiming();

		return $hasUpdate or !$this->onGround or abs($this->motionX) > 0.00001 or abs($this->motionY) > 0.00001 or abs($this->motionZ) > 0.00001;
	}

	public function burn($e){
		$fire = $this->virtualtick(40);
		if($fire){
			$level = $e->getLevel();
			if(0 < $level->getTime() and $level->getTime() < 13500){
				$pos = new Vector3($e->x ,$e->y, $e->z);
				$light = $this->getLight($level,$pos);
				if($light == 15){
					if($level->getWeather()->getWeather() === 0){
						if(!$this->isUnder($level,$pos)){
								$e->setOnFire(2);
						}
					}
				}
			}
		}
	}

	public function getLight($level,$pos) {
		$chunk = $level->getChunk($pos->x >> 4, $pos->z >> 4, false);
		$l = 0;
		if($chunk instanceof Chunk){
			$l = $chunk->getBlockSkyLight($pos->x & 0x0f, $pos->y & 0x7f, $pos->z & 0x0f);
			if($l < 15){
				$l = $chunk->getBlockLight($pos->x & 0x0f, $pos->y & 0x7f, $pos->z & 0x0f);
			}
		}
		return $l;
	}

	public function virtualtick($tick){
		$now = Server::getInstance()->getTick();
		
		if($now % $tick != 0){
			return false;
		}else{
			return true;
		}
	}

	public function isUnder($level,$pos){
		$mix = $pos->y + 2;
		$max = $pos->y + 10;
		$test = $pos;
		$result = false;
		for ($y0 = $mix;$y0 <= $max; $y0++) {
			$test->y = $y0;
				
			if ($level->getBlock($test)->getID() != 0) {
				$result = true;
			break;
			}
		}
		return $result;
	}

		public function getDrops(){
		$drops = [
			ItemItem::get(ItemItem::ROTTEN_FLESH, 0, 1)
		];
		if($this->lastDamageCause instanceof EntityDamageByEntityEvent and $this->lastDamageCause->getEntity() instanceof Player){
			if(mt_rand(0, 199) < 5){
				switch(mt_rand(0, 2)){
					case 0:
						$drops[] = ItemItem::get(ItemItem::IRON_INGOT, 0, 1);
						break;
					case 1:
						$drops[] = ItemItem::get(ItemItem::CARROT, 0, 1);
						break;
					case 2:
						$drops[] = ItemItem::get(ItemItem::POTATO, 0, 1);
						break;
				}
			}
		}

		return $drops;
	}

}
