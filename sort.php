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
  
  // copied from pico source, $headers as array gives ability to add additional metadata, e.g. header image
  private function read_file_meta($content) {
    $headers = array('index' => 'Index');

    foreach ($headers as $field => $regex) {
      if (preg_match('/^[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $content, $match) && $match[1]){
        $headers[ $field ] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $match[1]));
      } else {
        $headers[ $field ] = '';
      }
    }

    if (strlen($headers['index']) > 0) {
      $headers['index'] = (int)$headers['index'];
    } else {
      $headers['index'] = PHP_INT_MAX;
    }

    return $headers;
  }
  
  public function config_loaded(&$settings) {
    $this->base_url = $settings['base_url'];
    $this->content_dir = ROOT_DIR . $settings["content_dir"];
  }

  public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page) {
    // display pages with current tag if visiting tag/ url
    // display only pages with tags when visiting index page
    // this adds possiblity to distinct tagged pages (e.g. blog posts),
    // and untagged (e.g. static pages like "about")

    $new_pages = array();
    foreach ($pages as $page) {
      $file_url = substr($page["url"], strlen($this->base_url));
      if($file_url[strlen($file_url) - 1] == "/") $file_url .= 'index';
      $file_name = $this->content_dir . $file_url . ".md";
      
      // get metadata from page
      if (file_exists($file_name)) {
        $file_content = file_get_contents($file_name);
        $file_meta = $this->read_file_meta($file_content);
        $page = array_merge($page, $file_meta);
        array_push($new_pages, $page);
      }
    }
    usort($new_pages, function($a, $b) {
      $ar = false;
      if($a['index'] == $b['index']){
        $ar = $a['date'] < $b['date'];
      } else {
        $ar = $a['index'] > $b['index'];
      }
      return $ar;
    });
    $pages = $new_pages;
  }

}

?>