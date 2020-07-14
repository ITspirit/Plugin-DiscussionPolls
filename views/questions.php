<?php

if (!defined('APPLICATION')) {
    exit();
}

/* Copyright 2013-2014 Zachary Doll */
function DPRenderQuestionForm($PollForm, $DiscussionPoll, $Disabled, $Closed)
{
  echo '<div class="P" id="DP_Form">';
  if (!C('Plugins.DiscussionPolls.DisablePollTitle', false)) {
    echo $PollForm->Label('Discussion Poll Title', 'DP_Title');
    echo Wrap($PollForm->TextBox('DP_Title', array_merge($Disabled, ['maxlength' => 100, 'class' => 'InputBox BigInput'])), 'div', ['class' => 'TextBoxWrapper']);
  }
  echo Anchor(' ', '/plugin/discussionpolls/', ['id' => 'DP_PreviousQuestion', 'title' => T('Previous Question')]);

  $QuestionCount = 0;
  // set and the form data for existing questions and render a form
  foreach ($DiscussionPoll->Questions as $Question) {
    DPRenderQuestionField($PollForm, $QuestionCount, $Question, $Disabled);
    $QuestionCount++;
  }

  // If there is no data, render a single question form with 2 options to get started
  if (!$QuestionCount) {
    DPRenderQuestionField($PollForm);
  }

  // the end of the form
  if (!$Closed) {
    echo Anchor(T('Add a Question'), '/plugin/discussionpolls/addquestion/', ['id' => 'DP_NextQuestion', 'title' => T('Add a Question')]);
    echo Anchor(T('Add an Option'), '/plugin/discussionpolls/addoption', ['id' => 'DP_AddOption', 'title' => T('Add an Option')]);
  } else if ($QuestionCount > 1) {
    echo Anchor(T('Next Question'), '/plugin/discussionpolls/addquestion/', ['id' => 'DP_NextQuestion', 'title' => T('Next Question')]);
  }
  echo '</div>';
}

function DPRenderQuestionField($PollForm, $Index = 0, $Question = null, $Disabled = []) {
  // Gdn_Form has static $idCounters. Re-use the empty question field from definitions to circumvent wrong (incremented) IDs.
  $definition = Gdn::controller()->AddDefinition('DP_EmptyQuestion');
  if (is_null($Question) && $definition) {
      echo $definition;
      return;
  }
  echo '<fieldset id="DP_Question' . $Index . '" class="DP_Question">';
    echo $PollForm->Label(sprintf(T('Question #%d'), ($Index + 1)), "DP_Questions{$Index}");
    echo Wrap(
            $PollForm->TextBox(
              'DP_Questions[]',
              array_merge(
                $Disabled,
                [
                  'value' => (is_null($Question)) ? '' : $Question->Title,
                  'id' => "DP_Questions{$Index}",
                  'maxlength' => 100,
                  'class' => 'InputBox BigInput'
                ]
              )
            ),
            'div',
            ['class' => 'TextBoxWrapper']
          );

    // Render 2 blank options in the question doesn't have any options
    if(is_null($Question)) {
      DPRenderOptionField($PollForm, 0);
      DPRenderOptionField($PollForm, 1);
    }
    else {
      $j = 0;
      foreach ($Question->Options as $Option) {
        DPRenderOptionField($PollForm, $j, $Index, $Option, $Disabled);
        $j++;
      }
    }
    
  echo '</fieldset>';
}

function DPRenderOptionField($PollForm, $OIndex = 0, $QIndex = 0, $Option = null, $Disabled = [])
{
  echo $PollForm->Label(sprintf(T('Option #%d'), ($OIndex + 1)), "DP_Options{$QIndex}.{$OIndex}");
  echo Wrap(
          $PollForm->TextBox(
            "DP_Options{$QIndex}[]",
            array_merge(
              $Disabled,
              [
                'value' => (is_null($Option)) ? '' : $Option->Title,
                'id' => "DP_Options{$QIndex}.{$OIndex}",
                'maxlength' => 100,
                'class' => 'InputBox BigInput'
              ]
            )
          ),
          'div',
          ['class' => 'TextBoxWrapper']
        );
}
