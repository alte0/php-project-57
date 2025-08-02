<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskLabel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    public function index(FormRequest $request)
    {
        $label1 = $request->integer('label.first', 0);
        $label2 = $request->integer('label.second', 0);

        if ($label1 === $label2) {
            $selectedLabels = [];
            $tasks = [];
            $valuesTaskIdByLabelId = [];
            $isShowInfo = true;
        } else {
            $selectedLabels = (new Collection($request->query('label', [])))->values()->toArray();

            $taskLabelsObj = TaskLabel::query()
                ->whereIn('label_id', $selectedLabels)
                ->get();
            $valuesTaskIdByLabelId = $taskLabelsObj->groupBy('label_id')->map->pluck('task_id')->toArray();

            $taskListId = $taskLabelsObj
                ->pluck('task_id')
                ->toArray();

            $isShowInfo = false;
        }

        $labels = Label::all()->keyBy('id');

        $data = [
            'labels' => $labels,
            'label1' => $label1,
            'label2' => $label2,
            'label1Count' => array_key_exists($label1, $valuesTaskIdByLabelId) ? count($valuesTaskIdByLabelId[$label1]) : 0,
            'label2Count' => array_key_exists($label2, $valuesTaskIdByLabelId) ? count($valuesTaskIdByLabelId[$label2]) : 0,
            'isShowInfo' => $isShowInfo,
            'valuesTaskIdByLabelIdCount' => count($valuesTaskIdByLabelId),
        ];

        return view('report.index', $data);
    }
}
