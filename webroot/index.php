<?php
/**
 * This is a Anax pagecontroller. 
 *
 */
 
session_start();
 
 // Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php'; 

// Create services and inject into the app
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN); 
//$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');

// User Controller
$di->set('UsersController', function () use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

// Session Controller 
$di->setShared('UserSession', function () use ($di) {
    $session = new \Anax\Users\UserSession();
    $session->setDI($di);
    return $session;
});

// Comment Controller
$di->set('CommentController', function () use ($di) {
    $controller = new \Anax\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

// CDatabase as a service
$di->setShared('db', function () {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_mysql.php');
    $db->connect();
    return $db;
});

// CForm 
$di->set('form', '\Mos\HTMLForm\CForm');

// Form Controller
$di->set('FormController', function () use ($di) {
    $controller = new \Anax\HTMLForm\FormController();
    $controller->setDI($di);
    return $controller;
});

// ROUTES 

// Start page - Home route
$app->router->add('', function () use ($app) {
 
    $app->theme->setTitle("Welcome to the Forum");
    
    $app->views->add('start/flash', [], 'flash'); 
    $app->views->add('comment/user-card', [], 'sidebar'); 
    
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'viewActiveUsers',
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action' => 'viewLatest', 
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action' => 'viewPopularTags', 
    ]); 

    
});


// Login route
$app->router->add('login', function () use ($app) {
    $app->theme->setTitle("Login");
      
    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'login',
    ]);
});


// About page
$app->router->add('about', function () use ($app) {
    
    $app->theme->setTitle("About");
    
    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    $app->views->add('comment/user-card', [], 'sidebar'); 

    $app->views->add('me/page', [
        'content' => $content,
    ]);
 
});

// Forum page route
$app->router->add('comment', function () use ($app) { 
    $app->theme->setTitle("All questions");   
    
    $app->views->add('comment/user-card', [], 'sidebar');     
    
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action' => 'view', 
        'params' => ['comment']
    ]);
    
}); 

// Tags route
$app->router->add('tags', function () use ($app) { 
    $app->theme->setTitle("View tags");   
    
    $app->views->add('comment/user-card', [], 'sidebar');     
    
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action' => 'viewTags', 
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action' => 'viewPopularTags', 
    ]); 

});


//Forum Admin route
$app->router->add('comment-admin', function () use ($app) { 
    $app->theme->setTitle("Reset forum database");    
    
    $content = " ";
    
    $app->views->add('comment/user-card', [], 'sidebar');    
    
    $app->views->add('comment/admin', [
        'content' => $content,
    ]);
    
});
 

// Reset database tabel comment (Questions) 
$app->router->add('reset', function () use ($app) {

    $app->theme->setTitle("Reset forum/questions"); 
    //$app->db->setVerbose();
    $app->db->dropTableIfExists('comment')->execute();

    $app->db->createTable(
        'comment',
        [
            'id'       => ['integer', 'primary key', 'not null', 'auto_increment'],
            'page'     => ['varchar(20)', 'not null'],
            'name'     => ['varchar(80)'],
            'email'    => ['varchar(80)'],
            'web'      => ['varchar(80)'],
            'question' => ['varchar(255)'],
            'content'  => ['varchar(255)'],
            'tags'     => ['varchar(80)'],
            'created'  => ['datetime'],
            'updated'  => ['datetime'],
            'deleted'  => ['datetime'],
        ]
    )->execute();
    
    $app->db->insert(
        'comment',
        ['page', 'name', 'email', 'web', 'question', 'content', 'tags', 'created']
    );
    
    $now = gmdate('Y-m-d H:i:s');
    
    $app->db->execute([
        'comment',
        'Svan',
        'svan@bth.se',
        'www.bth.se',
        'New easy wax',
        'Start ski wax has new easy liquid kick wax with the kind of range the Start Grip tape has. Regular is 41 to 14F, and cold version is 30 to -4F. Can this be applied outside while skiing?.',
        'wax, cross-country',
    $now
    ]);
    
    $app->db->execute([
        'comment',
        'Wassberg',
        'wassberg@bth.se',
        'www.bth.se',
        'New racing technique',
        'The new comet of the World Cup, Johannes Klaebo has invented a new style that is really worked well for him. It is a kind of running or jumping stride / herringbone.',
        'racing, world-cup, cross-country',
    $now
    ]);
    
    $app->db->execute([
        'comment',
        'Kalla',
        'kalla@bth.se',
        'www.bth.se',
        'Do all top rollerski racers also race on snow?',
        'Is rollerski racing enough of its own separate sport that it sees some elites who do not do snow skiing?',
        'rollerski, racing',
    $now
    ]);
    
    $app->db->execute([
        'comment',
        'Wassberg',
        'wassberg@bth.se',
        'www.bth.se',
        'Media source for 2017/18 world cup races?',
        'Can anyone provide INTERNET sources/links to view the 2017/18 World Cup races (non-pay per view)?',
        'world-cup, cross-country, racing',
    $now
    ]);
    
    $content = $app->fileContent->get('reset.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    $app->views->add('comment/admin', [
        'content' => $content,
    ]);

});

// Reset db table answers
$app->router->add('reset-answers', function () use ($app) {
    $app->theme->setTitle("Reset forum/answers"); 
    //$app->db->setVerbose();
    $app->db->dropTableIfExists('answers')->execute();
    
    $app->db->createTable(
        'answers',
        [
            'id'         => ['integer', 'primary key', 'not null', 'auto_increment'],
            'name'       => ['varchar(80)'],
            'email'      => ['varchar(80)'],
            'questionID' => ['varchar(80)'],
            'content'    => ['varchar(255)'],
            'created'    => ['datetime'],
            'updated'    => ['datetime'],
            'deleted'    => ['datetime'],
        ]
    )->execute();
    
    $app->db->insert(
        'answers',
        ['name', 'email', 'questionID', 'content', 'created']
    );
    
    $now = gmdate('Y-m-d H:i:s');
    
    $app->db->execute([
        'Svan',
        'svan@bth.se',
        '1',
        'Interesting. I wonder how well it resists being scraped off by roots or pebbles.',
    $now
    ]);
    
    $app->db->execute([
        'Mogren',
        'mogren@bth.se',
        '1',
        'If anything like the grip tape- very much resistance to wear and scrape. Someone told me it can be applied in the field, but not as good as a clean base.',
    $now
    ]);    
    
    $app->db->execute([
        'Kalla',
        'kalla@bth.se',
        '2',
        'It is a both effective and funny looking technique. A diagonal stride without gliding.',
    $now
    ]); 
    
    $content = $app->fileContent->get('reset-answers.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    $app->views->add('comment/admin', [
        'content' => $content,
    ]);

}); 

// Reset db table replies
$app->router->add('reset-replies', function () use ($app) {
    $app->theme->setTitle("Reset forum/replies"); 
    //$app->db->setVerbose();
    $app->db->dropTableIfExists('replies')->execute();
    
    $app->db->createTable(
        'replies',
        [
            'id'          => ['integer', 'primary key', 'not null', 'auto_increment'],
            'name'        => ['varchar(80)'],
            'email'       => ['varchar(80)'],            
            'type'        => ['varchar(80)'], 
            'referenceID' => ['varchar(80)'],
            'content'     => ['varchar(255)'],
            'created'     => ['datetime'],
            'deleted'     => ['datetime'],
            'deletedBy'   => ['varchar(80)'],
        ]
    )->execute();
    
    $app->db->insert(
        'replies',
        ['name', 'email', 'type', 'referenceID', 'content', 'created']
    );
    
    $now = gmdate('Y-m-d H:i:s');
    
    $app->db->execute([
        'Mogren',
        'mogren@bth.se', 
        'question', 
        '1',
        'Interesting!',
    $now
    ]);
    
    $app->db->execute([
        'Wassberg',
        'wassberg@bth.se', 
        'answer', 
        '1',
        'Can you buy it in Sweden?',
    $now
    ]);    
    
    $app->db->execute([
        'Mogren',
        'mogren@bth.se', 
        'answer', 
        '1',
        'No, not yet',
    $now
    ]);
    
    $app->db->execute([
        'Svan',
        'svan@bth.se', 
        'question', 
        '2',
        'Who is Klaebo?',
    $now
    ]); 
    
    $content = $app->fileContent->get('reset-replies.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    $app->views->add('comment/admin', [
        'content' => $content,
    ]);

}); 


// Users route 
$app->router->add('users', function () use ($app) { 
    $app->dispatcher->forward([ 
    'controller' => 'users', 
    'action' => 'menu', 
    ]); 
}); 

// Reset db table user
$app->router->add('setup', function () use ($app) {
    
    $app->theme->setTitle("Reset dB"); 
    //$app->db->setVerbose();
    $app->db->dropTableIfExists('user')->execute();

    $app->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'active' => ['datetime'],
        ]
    )->execute();
    
    $app->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'active']
    );

    $now = gmdate('Y-m-d H:i:s');
    
    $app->db->execute([
        'admin',
        'admin@bth.se',
        'Super Admin',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        $now
    ]);

    $app->db->execute([
        'Wassberg',
        'wassberg@bth.se',
        'Thomas Wassberg',
        password_hash('Wassberg', PASSWORD_DEFAULT),
        $now,
        $now
    ]);
    
    $app->db->execute([
        'Svan',
        'svan@bth.se',
        'Gunde Svan',
        password_hash('Svan', PASSWORD_DEFAULT),
        $now,
        $now
    ]);
    
    $app->db->execute([
        'Mogren',
        'mogren@bth.se',
        'Torgny Mogren',
        password_hash('Mogren', PASSWORD_DEFAULT),
        $now,
        $now
    ]);
    
    $app->db->execute([
        'Kalla',
        'kalla@bth.se',
        'Charlotte Kalla',
        password_hash('Kalla', PASSWORD_DEFAULT),
        $now,
        $now
    ]);

    $app->dispatcher->forward([ 
        'controller' => 'users', 
        'action' => 'list', ]); 

});

// Check for matching routes and dispatch to controller/handler of route
$app->router->handle();

// Render the response using theme engine.
$app->theme->render();
