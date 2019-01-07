<?php

namespace Wenprise\Page;

use Wenprise\Foundation\DataContainer;
use Wenprise\Hook\IHook;
use Illuminate\View\View;

class PageBuilder
{
    /**
     * The page properties.
     *
     * @var DataContainer
     */
    protected $datas;

    /**
     * The page view file.
     *
     * @var \Illuminate\View\View
     */
    protected $view;

    /**
     * The page sections.
     *
     * @var array
     */
    protected $sections;

    /**
     * The page settings.
     *
     * @var array
     */
    protected $settings;

    /**
     * @var IHook
     */
    protected $action;

    /**
     * Build a Page instance.
     *
     * @param DataContainer $datas The page properties.
     * @param \Illuminate\View\View $view The page view file.
     * @param IHook $action The Action builder class.
     */
    public function __construct(DataContainer $datas, IHook $action, View $view = null)
    {
        $this->datas = $datas;
        $this->view = $view;
        $this->action = $action;
    }

    /**
     * @param string $slug The page slug name.
     * @param string $title The page display title.
     * @param string $parent The parent's page slug if a subpage.
     * @param \Illuminate\View\View $view The page main view file.
     *
     * @throws PageException
     *
     * @return \Wenprise\Page\PageBuilder
     */
    public function make($slug, $title, $parent = null, View $view = null)
    {
        $params = compact('slug', 'title');

        foreach ($params as $name => $param) {
            if (! is_string($param)) {
                throw new PageException('Invalid page parameter "'.$name.'"');
            }
        }

        // Check the view file.
        if (! is_null($view)) {
            $this->view = $view;
        }

        // Set the page properties.
        $this->datas['slug'] = $slug;
        $this->datas['title'] = $title;
        $this->datas['parent'] = $parent;
        $this->datas['args'] = [
            'capability' => 'manage_options', 'icon' => '', 'position' => null, 'tabs' => true, 'menu' => $title, 'function' => '',
        ];
        $this->datas['rules'] = [];

        return $this;
    }

    /**
     * Set the custom page. Allow user to override
     * the default page properties and add its own
     * properties.
     *
     * @param array $params
     *
     * @return \Wenprise\Page\PageBuilder
     */
    public function set(array $params = [])
    {
        $this->datas['args'] = array_merge($this->datas['args'], $params);

        // Trigger the 'admin_menu' event in order to register the page.
        $this->action->add('admin_menu', [$this, 'build']);

        return $this;
    }

    /**
     * Triggered by the 'admin_menu' action event.
     * Register/display the custom page in the WordPress admin.
     */
    public function build()
    {
        if (! is_null($this->datas['parent'])) {
            add_submenu_page($this->datas['parent'], $this->datas['title'], $this->datas['args']['menu'], $this->datas['args']['capability'], $this->datas['slug'], [
                $this, 'displayPage',
            ]);
        } else {
            add_menu_page($this->datas['title'], $this->datas['args']['menu'], $this->datas['args']['capability'], $this->datas['slug'], [
                $this, 'displayPage',
            ], $this->datas['args']['icon'], $this->datas['args']['position']);
        }
    }

    /**
     * Triggered by the 'add_menu_page' or 'add_submenu_page'.
     */
    public function displayPage()
    {
        // Share the page instance to the view.
        $this->with('__page', $this);
        echo $this->view->render();
    }

    /**
     * Return a page property value.
     *
     * @param string $property
     *
     * @return mixed
     */
    public function get($property = null)
    {
        return (isset($this->datas[$property])) ? $this->datas[$property] : '';
    }

    /**
     * Allow a user to pass custom datas to
     * the page view instance.
     *
     * @param string|array $key
     * @param mixed $value
     *
     * @return \Wenprise\Page\PageBuilder
     */
    public function with($key, $value = null)
    {
        $this->view->with($key, $value);

        return $this;
    }
}