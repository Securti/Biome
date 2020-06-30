<?php

/**
 * @name Biome
 * @main securti\biome\Biome
 * @author ["Securti"]
 * @version 0.1
 * @api 3.14.0
 * @description License : LGPL 3.0
 */
 
namespace securti\biome;
 
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\level\Level;

use pocketmine\utils\Config;

class Biome extends PluginBase implements Listener{

  public $data;
  
  public $biome = array("ocean" => 0, "plains" => 1, "desert" => 2, "mountains" => 3, "forest" => 4, "taiga" => 5, "swamp" => 6, "river" => 7, "hell" => 8, "ice_plains" => 10, "small_mountains" => 20, "birch_forest" => 27);
  
  public static $instance;
  
  public static function getInstance(){
  
    return self::$instance;
  }
  public function onLoad(){
  
    self::$instance = $this;
  }
  public function onEnable(){
  
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    
    @mkdir($this->getDataFolder());
    $this->BiomeData = new Config($this->getDataFolder() . "BiomeData.yml", Config::YAML);
    $this->data = $this->BiomeData->getAll();
    
    $this->setBiome($this->data);
  }
  public function setBiome($data){
  
    if(count($data) > 0){
    
      for($i = 0; $i < count($data); $i++){
      
        $t = $data[$i];
        
        $pos1 = explode(" ", $t[0]);
        $pos2 = explode(" ", $t[1]);
        
        $level = $this->getServer()->getLevelByName($t[2]);
        
        $biome = $this->getBiomeId($t[3]);
        
        if($biome !== null){
        
          $x_list = [$pos1[0], $pos2[0]];
          $z_list = [$pos1[1], $pos2[1]];
          
         for($x = min($x_list); $x <= max($x_list); $x++){
         
            for($z = min($z_list); $z <= max($z_list); $z++){
            
              $this->getServer()->loadLevel($t[2]);
              
              $level->loadChunk($x, $z);
              $level->setBiomeId($x, $z, $biome);
            }
          }
        }
      }
    }
  }
  public function getBiomeId($text){
  
    $text = strtolower($text);
    
    if(isset($this->biome[$text])){
    
      return $this->biome[$text];
    }
    
    return null;
  }
}