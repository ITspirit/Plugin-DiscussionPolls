<?php

if (!defined('APPLICATION')) {
    exit();
}

/* Copyright 2013-2014 Zachary Doll */
function DiscussionPollAnswerForm($PollForm, $Poll, $PartialAnswers)
{
  echo '<div class="DP_AnswerForm">';
  if (property_exists($Poll, 'Title') || C('Plugins.DiscussionPolls.DisablePollTitle', false)) {
    echo Gdn::formatService()->renderHtml($Poll->Title,  \Vanilla\Formatting\Formats\HtmlFormat::FORMAT_KEY);

    echo Gdn_Format::Text($Poll->Title);
    if (trim($Poll->Title) !== '') {
      echo '<hr />';
    }
  }
  echo $PollForm->Open(['action' => Url('/discussion/poll/submit/'), 'method' => 'post', 'ajax' => true]);
  echo $PollForm->Errors();

  $m = 0;
  // Render poll questions
  echo '<ol class="DP_AnswerQuestions">';
  foreach ($Poll->Questions as $Question) {
    echo '<li class="DP_AnswerQuestion">';
    echo $PollForm->Hidden('DP_AnswerQuestions[]', ['value' => $Question->QuestionID]);
    echo Wrap(Gdn_Format::Text($Question->Title), 'span');
    echo '<ol class="DP_AnswerOptions">';

    foreach ($Question->Options as $Option) {
      $dpAnswer = Gdn::formatService()->renderHtml($Option->Title, \Vanilla\Formatting\Formats\TextFormat::FORMAT_KEY);
      if (GetValue($Question->QuestionID, $PartialAnswers) == $Option->OptionID) {
        //fill in partial answer
        echo Wrap($PollForm->Radio('DP_Answer' . $m, $dpAnswer, ['Value' => $Option->OptionID, 'checked' => 'checked']), 'li');
      }
      else {
        echo Wrap($PollForm->Radio('DP_Answer' . $m, $dpAnswer, ['Value' => $Option->OptionID]), 'li');
      }
    }
    echo '</ol>';
    echo '</li>';
    $m++;
  }

  echo '</ol>';
  echo $PollForm->Close('Submit');
  echo '</div>';
}
