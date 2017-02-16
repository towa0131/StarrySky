<?php

/*
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
 */

namespace pocketmine\network\protocol;


class TransferPacket extends DataPacket{
    const NETWORK_ID = Info::TRANSFER_PACKET;
    
    	public $address;
    	public $port = 19132;
    
	public function getName(){
		return "TransferPacket";
	}

	public function decode(){

    	}
    
   	 public function encode(){
        $this->reset();
        $this->putString($this->address);
        $this->putLShort($this->port);
    }
}