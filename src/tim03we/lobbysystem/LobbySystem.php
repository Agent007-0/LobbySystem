<?php

/*
 * Copyright (c) 2019 tim03we  < https://github.com/tim03we >
 * Discord: tim03we | TP#9129
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * LobbySystem is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 */

namespace tim03we\lobbysystem;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class LobbySystem extends PluginBase {

    public static $instance;

    public static function getInstance(): LobbySystem
    {
        return self::$instance;
    }

    public function settings()
    {
        return new Config($this->getDataFolder() . "settings.yml", Config::YAML);
    }

    public function onEnable()
    {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->saveResource("settings.yml");
    }

    public function giveItems(Player $player, $get)
    {
        $player->getInventory()->setItem($get[3], Item::get($get[0], $get[1], $get[2])->setCustomName($get[4]));

    }
}