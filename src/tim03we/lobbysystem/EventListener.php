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

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event)
    {
        $event->getPlayer()->getInventory()->clearAll();
        $event->getPlayer()->setGamemode(LobbySystem::getInstance()->settings()->get("gamemode"));
        foreach (LobbySystem::getInstance()->settings()->get("items") as $item) {
            LobbySystem::getInstance()->giveItems($event->getPlayer(), explode("-", $item));
        }
        if(LobbySystem::getInstance()->settings()->getNested("messages.join") === "") $event->setJoinMessage(null);
        else $event->setJoinMessage(str_replace("{player}", $event->getPlayer(), LobbySystem::getInstance()->settings()->getNested("messages.join")));
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        if(LobbySystem::getInstance()->settings()->getNested("messages.quit") === "") $event->setQuitMessage(null);
        else $event->setQuitMessage(str_replace("{player}", $event->getPlayer(), LobbySystem::getInstance()->settings()->getNested("messages.quit")));
    }

    public function onBreak(BlockBreakEvent $event)
    {
        if(LobbySystem::getInstance()->settings()->getNested("events.break") == false) $event->setCancelled(true);
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        if(LobbySystem::getInstance()->settings()->getNested("events.place") == false) $event->setCancelled(true);
    }

    public function onExhaust(PlayerExhaustEvent $event)
    {
        if(LobbySystem::getInstance()->settings()->getNested("events.hunger") == false) $event->setCancelled(true);
    }

    public function onInventoryMove(InventoryTransactionEvent $event) {
        if(LobbySystem::getInstance()->settings()->getNested("events.inv-move") == false) $event->setCancelled(true);
    }

    public function onDrop(PlayerDropItemEvent $event) {
        if(LobbySystem::getInstance()->settings()->getNested("events.drop") == false) $event->setCancelled(true);
    }

    public function onPick(PlayerDropItemEvent $event) {
        if(LobbySystem::getInstance()->settings()->getNested("events.pick") == false) $event->setCancelled(true);
    }

    public function onInteract(PlayerInteractEvent $event) {
        $itemid = $event->getItem()->getId();
        $itemmeta = $event->getItem()->getDamage();
        $itemname = $event->getItem()->getName();
        foreach (LobbySystem::getInstance()->settings()->get("items") as $item) {
            $get = explode("-", $item);
            if($itemid == $get[0] && $itemmeta == $get[1] && $itemname === "$get[4]") LobbySystem::getInstance()->getServer()->dispatchCommand($event->getPlayer(), "$get[5]");
        }
    }
}