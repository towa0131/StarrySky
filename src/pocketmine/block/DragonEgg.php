<?php

/*
 *
 *     _____    _                                   _____   _
 *    / ___ \ _| |_   ___    _  __ _  __ _      __ / ___ \ | |   __      __
 *   | |___\_|_  __| / _ \  | |/ _| |/ _| \    / /| |___\_|| | __\ \    / /
 *    \___  \  | |  / / \ | | / / | / /  \ \  / /  \___  \ | |/ / \ \  / /
 *   | \___\ | | \_ | \_| |_|  /  |  /    \ \/ /  | \___\ || / /   \ \/ /
 *    \_____/   \__| \_____/|_|   |_|      \  /    \_____/ | |\ \   \  /
 *                                         / /             |_| \_\  / /
 *                                        / /                      / /
 *                                       /_/                      /_/
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author StarrySky Team
 * 
 *
*/

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

use pocketmine\Player;

use pocketmine\math\Vector3;

use pocketmine\level\Level;

class DragonEgg extends Fallable{

	protected $id = self::DRAGON_EGG;

	public function __construct(){

	}

	public function getName(){
		return "Dragon Egg";
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getHardness(){
		return 3;
	}

	public function canBeActivated(){
		return true;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$this->getLevel()->setBlock($block, $this, true, true);
		return true;
	}

	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), true, true);
		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		while(1){
		$newx = mt_rand($this->x - 13,$this->x + 13;
		$newy = mt_rand($this->y, $this->y + 3);
		$newz = mt_rand($this->z - 13, $this->z + 13);
		$newId = $this->getLevel()->getBlock(new Vector3($newx, $newy, $newz))->getID();
		if($newId == 0 || $newId == 8 || $newId == 9 || $newId == 10 || $newId == 11){
		$this->getLevel()->setBlock($this, new Air(), true, true);
		$this->getLevel()->setBlock(new Vector3($newx, $newy, $newz), $this, true, true);
			return true;
			}
		}
	}
}