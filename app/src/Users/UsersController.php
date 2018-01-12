<?php
namespace Anax\Users;

/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
    }
    
   /**
    * Start menu.
    *
    * @return void
    */
    public function menuAction()
    {

        $all = $this->users->findAll();

        $this->theme->setTitle("Administrate users");
        $this->views->add('users/user-menu', [
            'users' => $all,
            'title' => "Administrate users",
        ]);
        
        // Show user card
        $this->views->add('comment/user-card', [], 'sidebar');            
    }     
 
   /**
    * List all users.
    *
    * @return void
    */
    public function listAction()
    {

        $all = $this->users->findAll();

        $this->theme->setTitle("List all users");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "View all users",
        ]);
        
        // Show user card
        $this->views->add('comment/user-card', [], 'sidebar');            
    } 
    
    /**
     * Login a user.
     *
     * @return void
     */
    public function loginAction()
    {
        $form = new CFormLogin();
        
        $form->setDI($this->di);
        $form->check();
        $this->theme->setTitle("Login");
        
        // Show user card
        $this->di->views->add('comment/user-card', [], 'sidebar'); 
        
        $this->di->views->add("users/login", [
          'title' => 'Login',
          'content' => $form->getHTML()
          ]);
    }
    
    /**
     * Logout a user.
     *
     * @return void
     */   
    public function logoutAction()
    {
        unset($_SESSION['user']);
        $this->loginAction();
    }
    
    /**
     * List user with id. 
     * List all Questions, Answers and Replies made by a User.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        // Find user data
        $user = $this->users->find($id);

        // Find user's questions 
        $this->db
            ->select('*')
            ->from('comment')
            ->where("name = '$user->acronym'")
            ->execute();
        $questions = $this->db->fetchAll();        
        
        // Find user's answers
        $this->db
            ->select('*')
            ->from('answers')
            ->where("name = '$user->acronym'")
            ->execute();    
        $answers = $this->db->fetchAll();            
            
        // Find user's repies 
         $this->db
            ->select('*')
            ->from('replies')
            ->where("name = '$user->acronym'")
            ->execute();
        $replies = $this->db->fetchAll();       
        
        // Show user data
        $this->theme->setTitle("View user with id");
        $this->views->add('users/view', [
            'user' => $user,
            'questions' => $questions, 
            'answers' => $answers,
            'replies' => $replies,
            'title' => "View a user",
        ]);
        
        // Show user card
        $this->di->views->add('comment/user-card', [], 'sidebar'); 
       
    }
    
    /**
     * Find a User by Acronym/Name
     *
     * @return void
     */
    public function acronymAction($name)
    {
        $this->db
            ->select('*')
            ->from('user')
            ->where("acronym = '$name'")
            ->execute();
        
        $users = $this->db->fetchAll();
        
        foreach ($users as $user) {
            $userid = $user->id;
        }
        
        $this->idAction($userid);
    }
    
    
    /**
     * Add new user.
     *
     * @param string $acronym of user to add.
     *
     * @return void
     */
    public function addAction($acronym = null)
    {
        $form = new CFormUser();
        $form->setDI($this->di);
        $form->check();
        
        $this->theme->setTitle("Add a new user");
        $this->views->add('default/page', [
            'title' => "Add a new user using this form",
            'content' => $form->getHTML()
        ]);     

        // Show user card
        $this->di->views->add('comment/user-card', [], 'sidebar');         
    }
    
    
    /**
     * Update user.
     *
     * @param integer $id of user to update.
     *
     * @return void
     */
    public function updateAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
        //$this->di->session();
        $user = $this->users->find($id); 
        $form = new CFormUpdateUser($user);
        $form->setDI($this->di);
        $form->check();
        
        $this->theme->setTitle("Update user");
        
        $this->views->add('default/page', [
            'title' => "Update a user",
            'content' => $form->getHTML()
        ]);
        
        // Show user card
        $this->di->views->add('comment/user-card', [], 'sidebar');         
    } 
    
    
    /**
     * Delete user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $res = $this->users->delete($id);

        $url = $this->url->create('users/wastebasket');
        $this->response->redirect($url);
    }
    
    
    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function softDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $now = gmdate('Y-m-d H:i:s');

        $user = $this->users->find($id);

        $user->deleted = $now;
        $user->save();

        $url = $this->url->create('users/wastebasket');
        $this->response->redirect($url);
    }
    
    
    /**
     * Undo soft-delete for a user.
     *
     * @param integer $id of user.
     *
     * @return void
     */
    public function undoSoftDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $user = $this->users->find($id);

        $user->deleted = null;
        $user->save();

        $url = $this->url->create('users/list');
        $this->response->redirect($url);
    }
    
    
    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function activeAction()
    {
        $all = $this->users->query()
            ->where('active IS NOT NULL')
            ->andWhere('deleted is NULL')
            ->execute();

        $this->theme->setTitle("Users that are active");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users that are active and not deleted",
        ]);
        
        // Show user card
        $this->di->views->add('comment/user-card', [], 'sidebar');         
    }
    
    
    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function inactiveAction()
    {
        $all = $this->users->query()
            ->where('active is NULL')
            ->andWhere('deleted is NULL')
            ->execute();

        $this->theme->setTitle("Users that are inactive");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users that are inactive and not deleted",
        ]);
        
        // Show user card
        $this->di->views->add('comment/user-card', [], 'sidebar');         
    }
    
    
    /**
     * List all soft-deleted users.
     *
     * @return void
     */
    public function wastebasketAction()
    {
        $all = $this->users->query()
            ->where('deleted IS NOT NULL')
            ->execute();

        $this->theme->setTitle("Wastebasket");
        $this->views->add('users/waste-basket', [
        //$this->views->add('users/view-all-soft-delete', [
            'users' => $all,
            'title' => "Wastebasket",
        ]);
        
        // Show user card
        $this->di->views->add('comment/user-card', [], 'sidebar');         
    }
}
