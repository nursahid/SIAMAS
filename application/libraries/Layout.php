<?php

defined('BASEPATH') or exit('No direct script access allowed');

use MatthiasMullie\Minify;

/**
 * Output View for Admin or Front myIgniter.
 *
 * @author Kotaxdev
 **/
class Layout
{
    // CI
    private $CI;
    private $privilege = true;

    // Site
    private $title;
    private $logo;
    private $favicon;
    private $meta_tags = [];
    private $schema = [];

    // Assets plugins
    private $assets_plugins = [];

    // View wrapper
    private $view_wrapper = [];

    // Debugbar
    private $debugbar;

    // Custom Script
    private $view_script = [];

    // Cache Assets
    private $activeCacheAssets;

    public function __construct()
    {
        $this->CI = &get_instance();

        // Default config
        $site = $this->CI->config->item('site');
        $this->title = $site['title'];
        $this->logo = $site['logo'];
        $this->favicon = $site['favicon'];
        $this->debugbar = $this->CI->config->item('debugbar');
        $this->activeCacheAssets = false;
    }

    /**
     * Get script.
     *
     * @return Script
     **/
    public function get_script()
    {
        $this->view_script[] = '<script>var site = "' . rtrim(site_url(), '/') . '",base = "' . base_url() . '",segment = ["' . $this->CI->uri->segment(1) . '", "' . $this->CI->uri->segment(2) . '", "' . $this->CI->uri->segment(3) . '"],ctn="' . base64_encode($this->CI->config->item('csrf_token_name')) . '",ccn="' . base64_encode($this->CI->config->item('csrf_cookie_name')) . '";</script>';
        return implode('', $this->view_script);
    }

    /**
     * Set & Get title.
     *
     * @return title
     **/
    public function set_title($title)
    {
        $this->title = $title;
    }

    public function get_title($html = false)
    {
        if ($html) {
            return $this->title;
        } else {
            return '<title>'.$this->title.' | '.site['title'].'</title>';
        }
    }

    /**
     * Get logo.
     *
     * @return logo
     **/
    public function get_logo()
    {
        return $this->logo;
    }

    /**
     * Get favicon.
     *
     * @return favicon
     **/
    public function get_favicon($favicon = null)
    {
        if ($favicon != null) {
            return '<link rel="shortcut icon" type="image/x-icon" href="'.$favicon.'">';
        } else {
            return '<link rel="shortcut icon" type="image/x-icon" href="'.base_url($this->favicon).'">';
        }
    }

    /**
     * Set & Get meta tags.
     *
     * @return meta tags
     **/
    public function set_meta_tags($name, $content)
    {
        $this->meta_tags[] = '<meta name="'.$name.'" content="'.$content.'">';
    }

    public function get_meta_tags()
    {
        if (isset($this->meta_tags[0])) {
            return str_replace('>,<', '><', implode(',', $this->meta_tags));
        } else {
            return '';
        }
    }

    /**
     * Set & Get schema.
     *
     * @return Schema
     **/
    public function set_schema($property, $content)
    {
        $this->schema[] = '<meta property="'.$property.'" content="'.$content.'" />';
    }

    public function get_schema()
    {
        if (isset($this->schema[0])) {
            return str_replace('>,<', '><', implode(',', $this->schema));
        } else {
            return '';
        }
    }

    /**
     * Auth.
     *
     * @return bool
     **/
    public function auth()
    {
        if (!$this->CI->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        }
    }

    /**
     * Privilage.
     *
     * @return bool
     **/
    public function set_privilege($group)
    {
        if (!$this->CI->ion_auth->in_group($group)) {
            redirect('/', 'refresh');
        }
    }

    /**
     * Move all javascript in view wrapper into bottom.
     *
     * @return array
     **/
    public function script_to_bottom($view)
    {
        $view = explode('<script', $view);
        $script = '';
        $page = '';
        if (is_array($view)) {
            foreach ($view as $key => $view_element) {
                if (strpos($view_element, '</script>')) {
                    $script .= '<script'.$view_element;
                } else {
                    $page .= $view_element;
                }
            }
        } else {
            $page = $view;
        }
        $this->view_script[] = $script;

        return $page;
    }

    /**
     * Set active cache assets
     */
    public function setCacheAssets()
    {
        $this->activeCacheAssets = true;
    }

    /**
     * Set & Get assets url.
     *
     * @return URL
     **/
    public function set_assets($path, $type)
    {
        $this->assets_plugins[$type] = '';

        if ($this->activeCacheAssets) {
            $directory = 'assets/cache/' . $this->encodeDirectory($path);
            if ($type == 'styles') {
                $ext = 'css';
            } elseif ($type == 'scripts') {
                $ext = 'js';
            }
            $file = $directory . '/' . $type . '.min.' . $ext;

            if (!file_exists($file)) {
                if ($type == 'styles') {
                    $minifier = new Minify\CSS();
                } elseif ($type == 'scripts') {
                    $minifier = new Minify\JS();
                }
            
                foreach ($path as $value) {
                    $pathStripe = str_replace(base_url(), '', $value);
                    $minifier->add($pathStripe);
                }

                mkdir($directory);
                $minifier->minify($file);
            }

            $this->assets_plugins[$type] .= $this->assetsTag($file, $type);
        } else {
            foreach ($path as $value) {
                $pathStripe = str_replace(base_url(), '', $value);
                $baseUrl = true;
                if (strpos($pathStripe, 'https') || strrpos($pathStripe, 'http') || strpos($pathStripe, '//') || strpos($pathStripe, 'www.')) {
                    $baseUrl = false;
                }
                $this->assets_plugins[$type] .= $this->assetsTag($pathStripe, $type, $baseUrl);
            }
        }
    }

    /**
     * Assets tag
     * @param  string $type
     * @param  string $path
     * @param  boolean $baseUrl
     * @return string
     */
    public function assetsTag($path, $type = 'styles', $baseUrl = true)
    {
        if ($baseUrl) {
            $path = base_url($path);
        }

        if ($type == 'styles') {
            $tag = '<link rel="stylesheet" type="text/css" href="' . $path . '">';
        } elseif ($type == 'scripts') {
            $tag = '<script type="text/javascript" src="' . $path . '"></script>';
        }

        return $tag;
    }

    /**
     * Purge all cached assets
     * @param  string  $dir
     * @param  boolean $base
     * @return void
     */
    public function purgeAssets($dir = 'assets'.DIRECTORY_SEPARATOR.'cache', $base = true)
    {
        foreach (glob("{$dir}/*") as $file) {
            if (is_dir($file)) { 
                $this->purgeAssets($file, false);
            } else {
                unlink($file);
            }
        }
        if (!$base) {
            rmdir($dir);
        }
    }

    /**
     * Return assets
     * @param  string $type
     * @return string
     */
    public function get_assets($type)
    {
        $inlineScripts = '<script type="text/javascript">var site = "' . rtrim(site_url(), '/') . '",base = "' . base_url() . '",segment = ["' . $this->CI->uri->segment(1) . '", "' . $this->CI->uri->segment(2) . '", "' . $this->CI->uri->segment(3) . '"],ctn="' . base64_encode($this->CI->config->item('csrf_token_name')) . '",ccn="' . base64_encode($this->CI->config->item('csrf_cookie_name')) . '";';
        if ($type == 'scripts' && count($this->view_script) != 0) {
            $minifier = new Minify\JS();
            foreach ($this->view_script as $key => $value) {
                $minifier->add(str_replace('<script type="text/javascript">', '', str_replace('</script>', '', str_replace('<script>', '', $value))));
            }
            $inlineScripts .= $minifier->minify();
        }
        $inlineScripts .= '</script>';
        return $this->assets_plugins[$type] . $inlineScripts;
    }

    /**
     * Set & Get view wrapper.
     *
     * @return HTML
     **/
    public function set_wrapper($view, $data = null, $name = 'page', $wrap_script = true)
    {
        $view = $this->CI->load->view($view, $data, true);
        if ($wrap_script) {
            $view = $this->script_to_bottom($view);
        }
        $this->CI->load->clear_vars();
        $this->view_wrapper[$name] = $view;
    }

    public function get_wrapper($name)
    {
        return isset($this->view_wrapper[$name]) ? $this->view_wrapper[$name] : '';
    }

    /**
     * Output.
     *
     * @return HTML
     **/
    public function render($template = 'front', $data = null)
    {
        $template = 'template/' . $template . '_template';
        $data['assetsDecode'] = $this->encodeDirectory($path);
        $this->CI->load->view($template, $data);

        // Trigger Debugbar
        if ($this->debugbar == true) {
            $this->CI->output->enable_profiler(true);
        }
    }

    /**
     * list of all css / js into md5 for caching directory
     * @param  array  $path
     * @return string
     */
    public function encodeDirectory($path = [])
    {
        $sort = [];
        foreach ($path as $key => $value) {
            $newPath[] = end(explode('/', $value));
            $sort[] = filemtime($value);
        }
        arsort($sort);
        $lastTime = '';
        foreach ($sort as $key => $value) {
            $lastTime = $value;
            break;
        }
        $namingDirectory = str_replace('.min', '', implode('', $newPath));

        return substr(md5($namingDirectory . $lastTime), 0, 12);
    }

    /**
     * Get multy level menu.
     *
     * @return HTML
     **/
    public function get_menu($type = 'side menu')
    {
        // Privilage
        $this->CI->db->where('id_groups', null);
        $this->CI->db->join('groups_menu', 'groups_menu.id_menu = menu.id_menu', 'left');
        $this->CI->db->select('menu.id_menu');
        $menu_all = $this->CI->db->get('menu');
        foreach ($menu_all->result() as $new_menu_all) {
            $id_menu[] = $new_menu_all->id_menu;
        }

        if ($this->CI->ion_auth->logged_in()) {
            $this->CI->db->where('user_id', $this->CI->ion_auth->user()->row()->id);
            $this->CI->db->join('groups_menu', 'groups_menu.id_groups = users_groups.group_id', 'right');
            $this->CI->db->group_by('id_menu');
            $this->CI->db->select('id_menu');
            $groups = $this->CI->db->get('users_groups');
            foreach ($groups->result() as $groups) {
                $id_menu[] = $groups->id_menu;
            }
        }

        $where_type = 'type = "'.$type.'"';
        if (is_array($id_menu)) {
            $where_privilage = 'id_menu in ('.implode(',', $id_menu).') and ';
        } else {
            $where_privilage = null;
        }
        $this->CI->db->where($where_privilage.$where_type);
        $this->CI->db->join('menu_type', 'menu_type.id_menu_type = menu.id_menu_type', 'left');
        $this->CI->db->order_by('sort', 'ASC');
        $this->CI->db->order_by('label', 'ASC');
        $menus = $this->CI->db->get('menu');

        return $this->menus($menus->result_array());
    }

    /**
     * Get menu in array.
     *
     * @return array
     **/
    public function menus($menus, $parent_id = 0)
    {
        $new_menus = null;
        foreach ($menus as $menu) {
            if ($parent_id == $menu['parent_id']) {
                $new_menus[$menu['id_menu']] = [
                    'label' => $menu['label'],
                    'icon' => $menu['icon'],
                    'link' => $menu['link'],
                    'attr' => $menu['id'],
                    'level' => $menu['level'],
                    'children' => $this->menus($menus, $menu['id_menu']),
                ];
            }
        }

        return $new_menus;
    }

    /**
     * Pre loader language
     * @return void
     */
    public function sess_language()
    {
        if ($this->CI->session->userdata('lang')) {
            $this->CI->config->set_item('language', $this->CI->session->userdata('lang'));
            $this->CI->config->load('grocery_crud');
            $this->CI->config->set_item('grocery_crud_default_language', $this->CI->session->userdata('lang'));
        } else {
            $this->CI->config->load('config');
            $this->CI->session->set_userdata('lang_code', $this->CI->langCode[$this->CI->config->item('language')]);
            $this->CI->config->load('grocery_crud');
            $this->CI->config->set_item('grocery_crud_default_language', $this->CI->config->item('language'));
        }
        $this->CI->load->language('myigniter');
        $this->CI->load->language('ion_auth_lang');
        $this->CI->load->language('auth_lang');
    }

    /**
     * Return breadcrumb list $crumb = array.
     *
     * @return HTML
     **/
    public function breadcrumb($crumb, $homecrumb = null)
    {
        if ($homecrumb == null) {
            $homecrumb['icon'] = 'home';
            $homecrumb['label'] = 'Home';
			$homecrumb['link'] = 'admin/dashboard';
        }
        echo '<ol class="breadcrumb">';
        if (!isset($crumb)) {
            echo '<li class="active"><i class="fa fa-'.$homecrumb['icon'].'"></i> '.$homecrumb['label'].'</li>';
        } else {
            echo '<li><a href="'.site_url($homecrumb['link']).'"><i class="fa fa-dashboard"></i> '.$homecrumb['label'].'</a></li>';
            foreach ($crumb as $label => $link) {
                if ($link == '') {
                    $add_crumb = strpos(current_url(), '/add');
                    $edit_crumb = strpos(current_url(), '/edit');
                    $read_crumb = strpos(current_url(), '/read');
                    if ($add_crumb || $edit_crumb || $read_crumb) {
                        if ($add_crumb) {
                            $part_link = str_replace('/add', '', current_url());
                            $label_new = 'Add';
                        }
                        if ($edit_crumb) {
                            $part_link = strstr(current_url(), '/edit', true);
                            $label_new = 'Edit';
                        }
                        if ($read_crumb) {
                            $part_link = strstr(current_url(), '/read', true);
                            $label_new = 'Read';
                        }
                        echo '<li><a href="'.$part_link.'">'.$label.'</a></li>';
                        echo '<li class="active">'.$label_new.'</li>';
                    } else {
                        echo '<li class="active">'.$label.'</li>';
                    }
                } else {
                    echo '<li><a href="'.site_url($link).'">'.$label.'</a></li>';
                }
            }
        }
        echo '</ol>';
    }
}
