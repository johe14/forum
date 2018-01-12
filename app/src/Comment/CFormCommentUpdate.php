<?php
namespace Anax\Comment;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormCommentUpdate extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    // Variables     
    private $comment;     
    private $commentId = null; 
        
    /**
     * Constructor
     *
     */
    public function __construct($comment)
    {
        $this->comment = $comment; 
        
        parent::__construct([], [
            
            'question' => [
                'type'        => 'text',
                'label'       => 'Question heading:',
                'required'    => true,
                'value'       => empty($comment) ? null : html_entity_decode($comment->question),                
                'validation'  => ['not_empty'],
            ],             
            
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Text:',
                'required'    => true,
                'value'       => empty($comment) ? null : html_entity_decode($comment->content),
                'validation'  => ['not_empty'],
            ],
            
            'name' => [
                'type'        => 'hidden',
                'label'       => 'Acronym:',
                'required'    => true,
                'value'       => $_SESSION["user"]->acronym,
                'validation'  => ['not_empty'],
            ],
         
            'email' => [
                'type'        => 'hidden',
                'label'       => 'Email:',
                'required'    => true,
                'value'       => $_SESSION["user"]->email,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            
            'tags' => [
                'type'        => 'text',
                'label'       => 'Tags: (separate the tags with a comma ,)',
                'value'       => empty($comment) ? null : html_entity_decode($comment->tags),
            ], 
            
            'submit' => [
                'value'     => 'Save update',
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
        
        // Update an existing comment   
        $id = $this->comment->id;
        $created = $this->comment->created;
        $updated = $now; 
        
        $comment = new \Anax\Comment\Comment(); 
        $comment->setDI($this->di); 
                    
        $result = $comment->save([
            'id'        => $id, 
            'question'  => htmlentities($this->value('question')),
            'content'   => htmlentities($this->value('content')),
            'name'      => htmlentities($this->value('name')),
            'email'     => htmlentities($this->value('email')),
            'tags'      => htmlentities($this->value('tags')),
            'created'   => $created,
            'updated'   => $updated,
        ]); 

        $this->commentId = $id; 
        
        return $result;
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>Fail(): Form was submitted but I failed to process/save/validate it.</i></p>");
        $this->redirectTo('comment/');
    }

    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->AddOutput("<p><i>Form was submitted and the question is added.</i></p>");  
        $this->redirectTo("comment/id/{$this->commentId}"); 
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
