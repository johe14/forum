<?php

namespace Anax\Comment;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormReply extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;  
        
    // Variables
    private $referenceID;
    private $type;
    private $questionID; 
        
    /**
     * Constructor
     *
     */
    public function __construct($referenceID = null, $type = null, $questionID = null)
    {
        $this->referenceID = $referenceID;
        $this->type = $type; 
        $this->questionID = $questionID;
        
        parent::__construct([], [   
        
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Text:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => empty($reply) ? null : html_entity_decode($reply->content),
            ],
            
            'name' => [
                'type'        => 'hidden',
                'label'       => 'Name:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $_SESSION["user"]->acronym,
            ],
            
            'type' => [
                'type'      => 'hidden',
                'value'     => $this->type,
            ],
            
            'referenceID' => [
                'type'      => 'hidden',
                'value'     => $this->referenceID,
            ],            
         
            'email' => [
                'type'        => 'hidden',
                'label'       => 'Email:',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => $_SESSION["user"]->email,
            ], 
            
            'submit' => [
                'type'      => 'submit',
                'value'     => 'Submit comment',
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
        
        // A new reply
        $deleted = null; 
        
        $comment = new \Anax\Comment\Replies(); 
        $comment->setDI($this->di); 
                    
        $result = $comment->save([
            'content'     => htmlentities($this->value('content')),
            'name'        => htmlentities($this->value('name')),
            'type'        => $this->type,
            'referenceID' => $this->value('referenceID'),            
            'email'       => $this->value('email'),
            'created'     => $now,
            'deleted'     => $deleted,
        ]); 

        return $result;
    }     
    
    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>Fail(): Form was submitted but failed to process/save/validate it.</i></p>");
        $this->redirectTo("comment/id/" . $this->questionID);
    }

    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->AddOutput("<p><i>Form was submitted and the reply is added.</i></p>");  
        $this->redirectTo("comment/id/" . $this->questionID); 
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        $this->redirectTo("comment/id/" . $this->questionID);
    } 
}
