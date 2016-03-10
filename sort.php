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
class Sort extends AbstractPicoPlugin {

  protected $enabled = false;

  private $base_url;
  
  private $content_dir;
  
  public function onConfigLoaded(array &$config)
  {
    define('BASE_INT', 1000000);
  }

  public function onMetaHeaders(array &$headers)
  {
  	$headers['index'] = 'Index';
  }

  public function onSinglePageLoaded(array &$pageData)
  {
    $meta = $pageData['meta'];
    if(!$meta['index']){
      $pageData['index'] = BASE_INT;
    }else if($meta['index'] < 0){
      $pageData['index'] = BASE_INT - $meta['index'];
    }else{
      $pageData['index'] = $meta['index'];
    }
    var_dump($pageData['title']);
    var_dump($pageData['index']);
  }

  public function onPagesLoaded(
      array &$pages,
      array &$currentPage = null,
      array &$previousPage = null,
      array &$nextPage = null
  ) {
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