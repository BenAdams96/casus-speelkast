<?php

class SpeelkastController {
    private ItemManager $itemManager;

    public function __construct(ItemManager $itemManager) {
        $this->itemManager = $itemManager;
    }

    public function showItems() {
        $items = $this->itemManager->getAllItems();

        require "views/speelkast_view.php";
    }
}