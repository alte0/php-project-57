<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Models\Label;
use App\Models\TaskLabel;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    private function ensureAuthorized()
    {
        if (!Auth::check()) {
            throw new AuthorizationException();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('labels.index', ['labels' => Label::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->ensureAuthorized();

        return view('labels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabelRequest $request)
    {
        $this->ensureAuthorized();

        $label = new Label($request->validated());
        $label->save();

        return redirect()->route('labels.index')->with('messageLabel', trans('task_manager.messagesLabel.createSuccess'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label)
    {
        $this->ensureAuthorized();

        return view('labels.edit', ['label' => $label]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLabelRequest $request, Label $label)
    {
        $this->ensureAuthorized();
        $label->update($request->validated());

        return redirect()->route('labels.index')->with('messageLabel', trans('task_manager.messagesLabel.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        $this->ensureAuthorized();

        $countUsedLabel = TaskLabel::query()->where('label_id', $label->id)->count();

        if ($countUsedLabel === 0) {
            $messageLabel = trans('task_manager.messagesLabel.removedSuccess');
            $label->delete();
        } else {
            $messageLabel = trans('task_manager.messagesLabel.removedError');
        }

        return redirect()->route('labels.index')->with('messageLabel', $messageLabel);
    }
}
