<?php
namespace Anax\Comment;

/**
 * A controller for comments and admin related events.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
    
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->comments = new \Anax\Comment\Comment();
        $this->comments->setDI($this->di);
    }
    
    
    /**
     * Show comments on a page.
     *
     */
    public function viewAction($page) 
    { 
        $this->initialize();
        $comments = $this->comments->query() 
            ->where("page = ?") 
            ->execute(array($page));
            
        $this->views->add('comment/comments', [ 
            'comments' => $comments, 
            'page' => $page, 
        ]);
        
        $this->addAction($page);
    } 

    /**
     * Show latest comments
     *
     */
    public function viewLatestAction() 
    { 
        // Find 3 latest comments
        $this->db
        ->select('*')
        ->from('comment')
        ->orderBy("created  DESC")
        ->limit("3")
        ->execute();
        
        $latestComments = $this->db->fetchAll();  
            
        $this->views->add('start/page', [ 
            'comments' => $latestComments,  
        ]);

    } 
    
    /**
     * Show a question
     *
     */
    public function idAction($id)
    {
        if (!isset($id)) {
            die('Id is missing.');
        }
        
        // Find Question
        $comments = $this->comments->find($id);
        
        // Show Question
        $this->theme->setTitle("A Question");
        $this->di->views->add('comment/view', [
            'title' => "View a question",
            'comments' => $comments, 
        ]); 
        
        // Find replies/comments on Question
        $this->db
        ->select('*')
        ->from('replies')
        ->where("referenceID = $id AND type = 'question'")
        ->execute();
        
        $repliesQuestion = $this->db->fetchAll();        
        
        // Show Replies/comments on Question 
        $this->di->views->add('comment/replies', [
            'replies' => $repliesQuestion, 
        ]); 
        
        // Find Answers
        $this->db
        ->select('*')
        ->from('answers')
        ->where("questionID = $id")
        ->execute();
        
        $answers = $this->db->fetchAll();
        
        //dump($answers);
        
        // Find replies/comments on Answers 
        $this->db
        ->select('*')
        ->from('replies')
        ->where("type = 'answer'")
        ->execute();
        
        $repliesAnswer = $this->db->fetchAll();      
       
        // Show Answers and replies on Answers
        $this->views->add('comment/answers', [
            'title' => "Answers on the question",
            'answers' => $answers,
            'replies' => $repliesAnswer,
        ]);
        
        // Show user card
        $this->views->add('comment/user-card', [], 'sidebar'); 
        
        // Show form for adding Answers
        if (isset($_SESSION["user"])) {

            //$form = new \Anax\HTMLForm\CFormAnswer($id);
            $form = new CFormAnswer($id);
            $form->setDI($this->di);
            $form->check();
        
            //$this->theme->setTitle("Add answer to:");
        
            $this->di->views->add('comment/form2', [ 
                'title' => "Add answer", 
                'content' => $form->getHTML() 
            ]);
        }        
 
    } 
     
    /**
     * Show form to add comment
     *
     */
    public function addAction($page) 
    { 
        if (isset($_SESSION["user"])) {
    
            $form = new CFormComment($page);
            $form->setDI($this->di);
            $form->check();
        
            $this->di->views->add('comment/form2', [ 
                'title' => "Add new question", 
                'content' => $form->getHTML() 
            ]);
        }
    } 
    
    
    /**
     * Update comment.
     *
     */ 
    public function updateAction($id = null) 
    {
        if (!isset($id)) {
            die('Id is missing.');
        }
        $comment = $this->comments->find($id);

        $form = new CFormCommentUpdate($comment);
        $form->setDI($this->di);
        $form->check();
        
        $this->theme->setTitle("Update question:");
        
        $this->di->views->add('comment/form2', [ 
            'title' => "Update question", 
            'content' => $form->getHTML() 
        ]);
        
        // Show user card
        $this->views->add('comment/user-card', [], 'sidebar'); 
    }
    
    /**
     * Delete comment.
     *
     */ 
    public function deleteAction($id) 
    {
        if (!isset($id)) {
            die('Id is missing.');
        }
        $this->comments->delete($id);
        
        $this->response->redirect($this->url->create('comment'));    
    } 
    
    /**
     * Add reply on a Question 
     *
     */   
    public function addReplyQuestionAction($id)
    {
        if (!isset($id)) {
            die('Id is missing.');
        }
        
        if (isset($_SESSION["user"])) {

            $type = 'question';
            $questionID = $id; 

            $form = new CFormReply($id, $type, $questionID);
            $form->setDI($this->di);
            $form->check();
        
            $this->theme->setTitle("Add reply to question");
        
            $this->di->views->add('comment/form2', [ 
                'title' => "Add a comment", 
                'content' => $form->getHTML() 
            ]);
        }
        
        // Show user card
        $this->views->add('comment/user-card', [], 'sidebar'); 

    }

    /**
     * Add reply on an Answer 
     *
     */ 
    
    public function addReplyAnswerAction($id)
    {
        if (!isset($id)) {
            die('Id is missing.');
        }
        
        if (isset($_SESSION["user"])) {
    
            $type = 'answer';
            $answerID = $id; 
    
            // Find Question ID for this answer
       
            $this->db->select('questionID')
                 ->from('answers')
                 ->where("id = $answerID");      
            $this->db->execute();
        
            $results = $this->db->fetchAll();    
            
            foreach ($results as $result) :
                $questionID = $result->questionID; 
            endforeach;

            $form = new CFormReply($answerID, $type, $questionID);
            $form->setDI($this->di);
            $form->check();
        
            $this->theme->setTitle("Add reply to question");
        
            $this->di->views->add('comment/form2', [ 
                'title' => "Add a comment", 
                'content' => $form->getHTML() 
            ]);
        }  

    }

    /**
     * Delete a reply.
     *
     */ 
    public function deleteReplyAction($replyID) 
    {
        if (!isset($replyID)) {
            die('Id is missing.');
        }

        // Find reply post        
        $this->db->select("*")
             ->from('replies')
             ->where("id = $replyID");      
        $this->db->execute();
        
        $replies = $this->db->fetchAll(); 
        
        foreach ($replies as $reply) :
        
            if ($_SESSION["user"]->acronym == $reply->name or $_SESSION["user"]->acronym == "admin") :
        
                $now = gmdate('Y-m-d H:i:s');
                $user = $_SESSION["user"]->acronym; 
    
                // Set reply as deleted (soft)
                $this->db->update(
                    'replies',
                    [
                        'content' => 'Post deleted',
                        'deleted' => $now,
                        'deletedBy' => $user
                    ],
                    "id = $replyID"
                );    
                $this->db->execute(); 
                
                $type = $reply->type;
                $referenceID = $reply->referenceID; 
                
                // Find the Question ID        
                if ($type == 'answer') :
                    $questionID = $this->findQuestionID($referenceID);

                elseif ($type == 'question') :
                    $questionID = $referenceID; 

                else : 
                    die('Unknown type (should be Answer or Question).');
                
                endif;
        
            endif;    
            
        endforeach; 
        
        $this->response->redirect($this->url->create('comment/id/' . $questionID));                           
        
    }     
    
    /**
     * Find a Question ID for an Answer
     *
     */ 
    public function findQuestionID($answerID)
    {
        if (!isset($answerID)) {
            die('Answer Id is missing.');
        }
            
        $this->db->select("*")
             ->from('answers')
             ->where("id = $answerID");      
        $this->db->execute();
        
        $answers = $this->db->fetchAll(); 
    
        foreach ($answers as $answer) :
            $questionID = $answer->questionID; 
        endforeach; 
    
        return $questionID; 
    
    }
    
    /**
     * Find all tags
     *
     */
    public function viewTagsAction()
    {
        $comments = $this->comments->query()->execute();
        
        $tags = $this->sortTags($comments);
       
        $this->theme->setTitle("All tags");
        $this->di->views->add('comment/tags', [
            'title' => "View tags",
            'tags' => $tags, 
        ]);        

    }
    
    /**
     * Sort tags
     *
     */
    public function sortTags($comments)
    {    
        $sortedTags = null;
        foreach ($comments as $comment) : 
            $sortedTags .= $comment->tags . ", "; 
        endforeach;
        
        $sortedTags = explode(", ", $sortedTags);
        $sortedTags = array_unique($sortedTags); 
        
        return $sortedTags; 
        
    }
    
    /**
     * Find questions related to a tag
     *
     */
    public function findTagQuestionAction($tag)
    {
        if (!isset($tag)) {
            die('Tag is missing.');
        }
            
        $this->db->select("*")
             ->from('comment')
             ->where("tags LIKE '%$tag%'");      
        $this->db->execute();
        
        $comments = $this->db->fetchAll(); 
    
        $this->theme->setTitle("Questions related to tag");
        $this->views->add('comment/viewTagQuestion', [
            'title' => "Questions with tag: " . $tag,
            'comments' => $comments,
        ]);    

        // Show user card
        $this->views->add('comment/user-card', [], 'sidebar');         
        
    }     
     
    /**
     * Find most popular tags
     *
     */
    public function viewPopularTagsAction()
    {
        $comments = $this->comments->query()->execute();
        
        $popularTags = null;
        foreach ($comments as $comment) :
            $popularTags .= $comment->tags . ", ";            
        endforeach;
        
        // Split a string by string, returns an array of strings 
        $popularTags = explode(", ", $popularTags);
        
        // Counts all the values of the array
        $popularTags = array_count_values($popularTags);
        
        // Sort the array in reverse order
        arsort($popularTags);
     
        // Extract a slice of the array
        $popularTags = array_slice($popularTags, 0, 5);
        
        $this->di->views->add('comment/popular-tags', [
            'title' => "Popular tags",
            'tags' => $popularTags, 
        ]);
     
    } 
    
    /**
     * Find most acitve users
     *
     */   
    public function viewActiveUsersAction()
    {
        $html = "<div class='block-right'>";
        $html .= "<h3>Most active users</h3>";

        // Get all comments/questions
        $comments = $this->comments->query()->execute();

        $html .= "<p>Questions:</p>";
        $html .= $this->sortUsers($comments); 
        
        // Get all answers (not deleted)
        $this->db
            ->select('*')
            ->from('answers')
            ->where("deleted is NULL")
            ->execute();
        
        $answers = $this->db->fetchAll(); 
        
        $html .= "<p>Answers:</p>";
        $html .= $this->sortUsers($answers); 
        
        // Get all replies (not deleted)
        $this->db
            ->select('*')
            ->from('replies')
            ->where("deleted is NULL")
            ->execute();
        
        $replies = $this->db->fetchAll(); 
        
        $html .= "<p>Comments:</p>";
        $html .= $this->sortUsers($replies); 
        $html .= "</div>"; 
        
        $this->views->addString($html, 'sidebar');
    }

    /**
     * Sort the Active Users array
     *
     */   
    public function sortUsers($comments)
    {
        $users = null;
        
        foreach ($comments as $comment) : 
            $users .= $comment->name . ", "; 
        endforeach;

        // Split a string by string, returns an array of strings 
        $users = explode(", ", $users);
        
        // Counts all the values of the array
        $users = array_count_values($users);
        
        // Sort the array in reverse order
        arsort($users);
        
        // Extract a slice of the array
        $users = array_slice($users, 0, 3);
        
        $html = null;
        
        foreach ($users as $user => $count) :
            if (!empty($user)) :
                $html .=  '<a class="block-right-link" title="View user profile" href="' . $this->url->create('users/acronym/') . "/" . $user . '">' . sprintf('%s (%u) ', $user, $count) . '</a>';  
                $html .= "<br>"; 
            endif;   
        endforeach;     
        
        $html .= "<br>";
        
        return $html; 
    }     
}
