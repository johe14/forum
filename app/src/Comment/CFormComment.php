<?php
namespace Anax\Comment;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormComment extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    // Variables     
    private $comment;     
        
    /**
     * Constructor
     *
     */
    public function __construct($page, $comment = null)
    {
        $this->comment = $comment; 
        
        parent::__construct([], [
        
            'page' => [
                'type'        => 'hidden',
                'value'       => $page,
            ],
            
            'question' => [
                'type'        => 'text',
                'label'       => 'Question heading:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],            
                       
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Text:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'name' => [
                'type'        => 'hidden',
                'label'       => 'Name:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $_SESSION["user"]->acronym,
            ],
         
            'email' => [
                'type'        => 'hidden',
                'label'       => 'Email:',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => $_SESSION["user"]->email,
            ], 
            
            'tags' => [
                'type'        => 'text',
                'label'       => 'Tags: (separate the tags with a comma ,)',
            ], 
            
            'submit' => [
                'value'     => 'Submit question',
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
        
        // A new comment
        $id = null;
        $created = $now;
        $updated = null; 
        
        $comment = new \Anax\Comment\Comment(); 
        $comment->setDI($this->di); 
                    
        $result = $comment->save([
            'id'        => $id, 
            'page'      => $this->value('page'),
            'question'  => htmlentities($this->value('question')),
            'content'   => htmlentities($this->value('content')),
            'name'      => htmlentities($this->value('name')),
            'email'     => htmlentities($this->value('email')),
            'tags'      => htmlentities($this->value('tags')),
            'created'   => $now,
            'updated'   => $updated,
        ]); 

        return $result;
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it.</i></p>");
        //$this->redirectTo($this->value('page'));
        $url = $this->value('page');
        if ($url === 'me') {
            $url = '';
        }  
        $this->redirectTo($url);
    }

    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->AddOutput("<p><i>callbackSuccess(): Form was submitted and the question is added.</i></p>");
        $url = $this->value('page');
        if ($url === 'me') {
            $url = '';
        }  
        $this->redirectTo($url);
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
