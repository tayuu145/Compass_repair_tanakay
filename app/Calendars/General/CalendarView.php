<?php

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView
{

  private $carbon;
  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th class="day-sat">土</th>';
    $html[] = '<th class="day-sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';

      $days = $week->getDays();
      foreach ($days as $day) {
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");

        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          $html[] = '<td class="calendar-td past-date  ' . $day->getClassName() . '">'; // 過去日の場合、"past-date"クラスを追加
        } else {
          $html[] = '<td class="calendar-td ' . $day->getClassName() . '">';
        }
        $html[] = $day->render();
        //予約ある場合　　　　　　　　　　　　　↓開講日などとりだしているやつ
        if (in_array($day->everyDay(), $day->authReserveDay())) {
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
          if ($reservePart == 1) {
            $reservePart = "リモ1部";
          } else if ($reservePart == 2) {
            $reservePart = "リモ2部";
          } else if ($reservePart == 3) {
            $reservePart = "リモ3部";
          }
          // 過去日に
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $html[] = '<p class="m-auto p-0 w-75 clor-kuro" style="font-size:12px">' . $reservePart . '</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="' . $day->authReserveDate($day->everyDay())->first()->setting_part . '" form="reserveParts">';
            // 今日以降に
          } else {
            $html[] = '<div class="calendar-modal-open" getAria="' . $reservePart . '" getData="' . $day->authReserveDate($day->everyDay())->first()->setting_reserve . '" getPart="' . $day->authReserveDate($day->everyDay())->first()->setting_part . '" >';
            $html[] = '<button type="submit" class="btn btn-danger p-0 w-75" name="delet" style="font-size:12px" form="deleteParts" value="' . $day->authReserveDate($day->everyDay())->first()->setting_reserve . '">' . $reservePart . '</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="' . $day->authReserveDate($day->everyDay())->first()->setting_part . '" form="reserveParts">';
            // $html[] = '<input type="hidden" name="getPart[0]" value="' . $day->authReserveDate($day->everyDay())->first()->setting_part . '" form="deleteParts">';
            // $html[] = '<input type="hidden" value="' . $this->carbon->format('Y-m-d') . '" name="" form="deleteParts">';
            $html[] = '</div>';


            $html[] = '<div class="modal js-modal">';
            $html[] = '<div class="modal__bg js-modal-close"></div>';
            $html[] = '<div class="modal__content">';
            $html[] = '<div class="w-100">';
            $html[] = '<div class="modal-getData yoyaku-sp text-left">';
            $html[] = '<span>予約日：<input type="text" class="border-none" name="getData" form="deleteParts" readonly ></span>';
            $html[] = '</div>';
            $html[] = '<div class="modal-getAria yoyaku-sp text-left">';
            $html[] = '<span>時間：<input type="text" class="border-none" name="getAria" form="deleteParts" value="getAria" readonly></span>';
            $html[] = '</div>';
            $html[] = '<div class="yoyaku-sp text-left">';
            $html[] = '<p>上記の予約をキャンセルしてもよろしいですか？</p>';
            $html[] = '</div>';
            $html[] = '<div class="modal-getPart">';
            $html[] = '<input type="hidden"  name="getPart" form="deleteParts" value="getPart">';
            $html[] = '</div>';
            $html[] = '<div class="w-50 m-auto edit-modal-btn d-flex">';
            $html[] = '<a class="js-modal-close btn btn-danger d-inline-block" href="">閉じる</a>';
            $html[] = '<input type="hidden" class="edit-modal-hidden" name="post_id" value="">';
            $html[] = '<input type="submit" class="btn btn-primary d-block" value="キャンセル" form="deleteParts">';
            $html[] = '</div>';
            $html[] = '</div>';
            $html[] = '</form>';
            $html[] = '</div>';
            $html[] = '</div>';
          }
          // 予約ない場合
        } else {
          // 過去に
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            // 　　　　　　      ↑過去日ならの条件式
            // if (!empty($reservePart)) {
            //   $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">' . $reservePart . '</p>';
            // } else {
            $html[] = '<p class="m-auto p-0 w-75 clor-kuro" style="font-size:12px">受付終了</p>';

            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            // 今日以降
          } else {
            $html[] = $day->selectPart($day->everyDay());
          }
        }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';


    return implode('', $html);
  }

  protected function getWeeks()
  {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while ($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
