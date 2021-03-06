<?php
namespace Anax\HTMLForm;

/**
 * Form for login User.
 *
 */
class UserFormLogin extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct($user = null)
    {
        parent::__construct([], [

            'arconym' => [
                'type'        => 'text',
                'label'       => 'Username',
                'required'    => true, 
                'validation'  => ['not_empty'],
                'value'       => empty($user) ? null : $user->acronym,
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
                'checked'     => true,
            ],
            
            'submit' => [
                'value'     => 'OK',
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'], 
            ], 
        ]);
        
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
        
        $acronym = $this->Value('acronym');
        $password = $this->Value('password');
        $result = $this->di->UserSession->login($acronym, $password);        

        return $result;
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but failed to process/save/validate it</i></p>");
        $this->redirectTo(); 
    }

    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $url = $this->di->url->create('users/list'); 
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
