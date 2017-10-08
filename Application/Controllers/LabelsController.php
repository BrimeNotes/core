<?php

namespace Brime\Controllers;


use Brime\Core\Controller;
use Brime\Core\Framework\Model;
use Brime\Core\Framework\Helper;
use Brime\Core\Helpers\Http;

class LabelsController extends Controller
{
    /**
     * @var \Brime\Models\Label
     */
    private $label;

    /**
     * @var \Brime\Core\Helpers\Request
     */
    private $Request;

    public function __construct(Model $model, Helper $helper)
    {
        $this->label = $model->get('Label');

        $this->Request = $helper->get('Request');

        parent::__construct($model, $helper);
    }

    public function addLabel() {
        $label = $this->Request->post('label');

        if ($this->label->doesLabelExist($label)) {
            $this->View->renderJSON([
                "status" => "error",
                "message" => "Label already exists"
            ],
            Http::STATUS_CONFLICT);
            return;
        }

        $this->label->create($label);
        $this->View->renderJSON(
            [
                "status" => "success",
                "message" => "Label created successfully"
            ],
            Http::STATUS_CREATED
        );
        return;
    }

    public function getAllLabels() {
        $this->View->renderJSON(
            $this->label->get(),
            Http::STATUS_OK
        );
        return;
    }
}