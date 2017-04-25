<?php

namespace Brime\Controllers;

use Brime\Core\Controller;
use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;
use Brime\Core\Helpers\Http;
use Brime\Core\Helpers\Request;

use Brime\Models\Notes;

class NotesController extends Controller
{
    /**
     * @var Notes
     */
    private $notes;

    /**
     * @var Request
     */
    private $Request;

    public function __construct(Model $model, Helper $helper)
    {
        $this->notes = $model->get('Notes');

        $this->Request = $helper->get('Request');

        parent::__construct($model, $helper);
    }


    public function get($userId, $noteId='')
    {
        if ($noteId === '') {
            // TODO: Show all the notes
            // Get Notes by a single user
        }

        $note = $this->notes->getSingleNote($noteId);
        if ($note->userid != $userId) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Unauthorised access."
                ],
                Http::STATUS_FORBIDDEN
            );
            return;
        }

        $this->View->renderJSON(
            $note,
            Http::STATUS_OK
        );
        return;
    }

    public function add()
    {
        if ($this->Request->isPost()) {
            if (isset($_POST['title'], $_POST['content'], $_POST['label'], $_POST['author'])) {
                $title = strip_tags($_POST['title']);
                $content = strip_tags($_POST['content']);
                $label = strip_tags($_POST['label']);
                $author = strip_tags($_POST['author']);
                if (!$this->notes->addNote($title, $content, $label, $author)) {
                    $this->View->renderJSON(
                        [
                            'message' => 'Could not add note'
                        ],
                        Http::STATUS_BAD_REQUEST
                    );
                } else {
                    $this->View->renderJSON(
                        [
                            'message' => 'Note added successfully'
                        ],
                        Http::STATUS_OK
                    );
                }
            }
        } else {
            $this->View->renderJSON(
                [
                    'message' => 'You should not be here'
                ],
                Http::STATUS_FORBIDDEN);
        }
    }

    public function update() {}
    public function delete() {}
}