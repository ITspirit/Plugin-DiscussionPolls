<?php

if (!defined('APPLICATION')) {
    exit();
}

/* Copyright 2013 Zachary Doll */
echo Wrap(T($this->Data['Title']), 'h1');

echo $this->Form->Open();
echo $this->Form->Errors();

echo Wrap(
    Wrap(
        Wrap(
                $this->Form->Label(T('Show Results'), 'Plugins.DiscussionPolls.EnableShowResults')
                , 'div', ['class' => 'label-wrap']
        ) .
        Wrap (
            $this->Form->CheckBox('Plugins.DiscussionPolls.EnableShowResults', T('Allow users to view results without voting'))
            , 'div', ['class' => 'input-wrap']) .

        Wrap(
                $this->Form->Label(T('Poll Title'), 'Plugins.DiscussionPolls.DisablePollTitle')
                , 'div', ['class' => 'label-wrap']
        ) .
        Wrap(
            $this->Form->CheckBox('Plugins.DiscussionPolls.DisablePollTitle', T('Disable poll titles'))
            , 'div', ['class' => 'input-wrap'])

        , 'li', ['class' => 'form-group']
    )
    , 'ul'
);

echo $this->Form->Close('Save');
