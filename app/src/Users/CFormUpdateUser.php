<?php

namespace Anax\Users;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUpdateUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
    * Properties
    *
    */
    private $userId = null;        
        

    /**
     * Constructor
     *
     */
    public function __construct($user)
    {
        parent::__construct([], [

            'id' => [ 
                'type'       => 'hidden',
                'label'      => 'Id',
                'value'      => $user->id, 
                'validation' => ['not_empty'], 
            ], 


            'arconym' => [
                'type'        => 'hidden',
                'label'       => 'Username',
                'required'    => true, 
                'value'       => $user->acronym,
                'validation'  => ['not_empty'],
            ],
            
            'name' => [
                'type'        => 'text',
                'label'       => 'Name',
                'required'    => true,
                'value'       => $user->name,
                'validation'  => ['not_empty'],
            ],
         
            'email' => [
                'type'        => 'text',
                'label'       => 'Email',
                'required'    => true,
                'value'       => $user->email, 
                'validation'  => ['not_empty', 'email_adress'], 
            ], 
        
            'password' => [
                'type'        => 'password',
                'label'       => 'Password (min 8 characters)',
                'required'    => true,
                'minlength'   => 8,
                'validation'  => ['not_empty'],
            ], 
            
            'active'  => [
                'type'        => 'checkbox',
                'label'       => 'Active',
                'checked'     => $user->active == null ? false : true,
            ],
            
            'submit' => [
                'value'       => 'Update',
                'type'        => 'submit',
                'callback'    => [$this, 'callbackSubmit'], 
            ], 
        ]);
            
        $this->userId = $user->id; 
        
    }       



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }


    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        $now = gmdate('Y-m-d H:i:s'); 
        
        $active = $now;

        $users = new User(); 
        $users->setDI($this->di); 
                    
        $result = $users->save([
            'id'        => $this->Value('id'),
            'acronym'   => $this->Value('arconym'),
            'email'     => $this->Value('email'),
            'name'      => $this->Value('name'),
            'password'  => password_hash($this->value('password'), PASSWORD_DEFAULT),
            'active'    => $active,
            'updated'   => $now,
        ]); 

        return $result;
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        $this->redirectTo(); 
    }

    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $url = $this->di->url->create("users/id/{$this->userId}"); 
        //$url = $this->di->url->create("users/list"); 
        $this->di->response->redirect($url); 
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        $this->redirectTo();
    }
}
