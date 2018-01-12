<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [
    
        // This is a menu item
        'start'  => [
            'text'  => 'Home',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'The start page'
        ],
        
        // This is a menu item
        'redovisning'  => [
            'text'  => 'About',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'About this site',
        ],
        
        // This is a menu item
        'comments' => [
            'text'  =>'Forum',
            'url'   => $this->di->get('url')->create('comment'),
            'title' => 'Forum',
            'mark-if-parent-of' => 'comment',
            
            'submenu' => [
            
                // A sub menu item
                'items' => [
                
                    'reset' => [
                        'text'   => 'Reset the forum db',
                        'url'    => $this->di->get('url')->create('comment-admin'),
                        'title'  => 'Reset the forum db',
                    ]
                ]
            ]       
        ],
        
        // This is a menu item
        'tags'  => [
            'text'  => 'Tags',
            'url'   => $this->di->get('url')->create('tags'),
            'title' => 'View all tags',
        ],        
                                                                    
        
        // This is a menu item
        'users'  => [
            'text'  => 'Users',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Show user',    
            
        ],
        
        // This is a menu item
        'Login'  => [
            'text'  => 'Login',
            'url'   => $this->di->get('url')->create('login'),
            'title' => 'Login'
        ],
        

        
    ], 

    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
