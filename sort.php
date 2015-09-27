<?php
/**
 * Pico Sort Plugin
 *
 * @author TakamiChie
 * @link http://onpu-tamago.net/
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
// ref: https://github.com/szymonkaliski/Pico-Tags-Plugin/blob/master/pico_tags.php
class Sort{

  private $base_url;
  
  private $content_dir;
  
  public function config_loaded(&$settings) {
    define('BASE_INT', 1000000);
  }
  
  public function before_read_file_meta(&$headers)
  {
  	$headers['index'] = 'Index';
  }

  public function get_page_data(&$data, $page_meta)
  {
    if(!$page_meta['index']){
      $data['index'] = BASE_INT;
    }else if($page_meta['index'] < 0){
      $data['index'] = BASE_INT - $page_meta['index'];
    }else{
      $data['index'] = $page_meta['index'];
    }
  }

  public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page) {
    usort($pages, function($a, $b) {
      $ar = false;
      if($a['index'] == $b['index']){
        $ar = $a['date'] < $b['date'];
      } else {
        $ar = $a['index'] > $b['index'];
      }
      return $ar;
    });
  }

}

?>