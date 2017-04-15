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

namespace pocketmine\utils;

use pocketmine\Server;

class IniLoader{

/*
=============================
Demo-01
[php]
	$ini = new IniLoader();
	$result = $ini->iniLoad(Server::getInstance()->getDataPath() . "test/test.ini", "ini.test");
	var_dump($result);
=============================
[ini]
	ini.test=test
=============================
[Output]
	string(4) "test"
=============================
Demo-02
[php]
	$ini = new IniLoader();
	$result = $ini->iniLoad(Server::getInstance()->getDataPath() . "test/test.ini", "ini.test", ["red", "yellow"]);
	var_dump($result);
=============================
[ini]
	ini.test=apple-{ini0}-lemon-{ini1}
=============================
[Output]
	string(22) "apple-red-lemon-yellow"
=============================
*/
	public function iniLoad($path, $str, $array = []){
	$content = file_get_contents($path);
	$result = "";
	foreach(explode("\n", $content) as $line){
		$line = trim($line);
		$t = explode("=", $line, 2);
		if($t[0] == $str){
			$result .= $t[1];
			if(count($array) == 0){
			return $result;
				}else{
			$result = $t[1];
		for($i=0;$i<count($array);$i++){
			$result = str_replace("{ini".$i."}", $array[$i], $result);
				}
			return $result;
			}
		}else{
			continue;
			}
		}
	}
}