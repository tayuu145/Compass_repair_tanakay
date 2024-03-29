@extends('layouts.sidebar')

@section('content')
<div class="search_content w-100 border d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="border one_person kadomaru">
      <div>
        <span>ID : </span><span>{{ $user->id }}</span>
      </div>
      <div><span>名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span>{{ $user->over_name }}</span>
          <span>{{ $user->under_name }}</span>
        </a>
      </div>
      <div>
        <span>カナ : </span>
        <span>({{ $user->over_name_kana }}</span>
        <span>{{ $user->under_name_kana }})</span>
      </div>
      <div>
        @if($user->sex == 1)
        <span>性別 : </span><span>男</span>
        @else
        <span>性別 : </span><span>女</span>
        @endif
      </div>
      <div>
        <span>生年月日 : </span><span>{{ $user->birth_day }}</span>
      </div>
      <div>
        @if($user->role == 1)
        <span>権限 : </span><span>教師(国語)</span>
        @elseif($user->role == 2)
        <span>権限 : </span><span>教師(数学)</span>
        @elseif($user->role == 3)
        <span>権限 : </span><span>講師(英語)</span>
        @else
        <span>権限 : </span><span>生徒</span>
        @endif
      </div>
      <div>
        <!-- roleが値が4になっていれば -->
        @if($user->role == 4)
        <span>選択科目 :</span>
        <!-- foreachでallで取得した特定のカラムだけ表示 -->
        @foreach ($user->subjects as $subject)
        <span>{{$subject->subject}}</span>
        @endforeach
        @endif
      </div>
    </div>
    @endforeach
  </div>
  <div class="search_area border">
    <div class="">
      <div class="margin-10">
        <span>検索</span><br>
        <input type="text" class="free_word search-s" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
      </div>
      <div class="margin-10">
        <lavel>カテゴリ</lavel><br>
        <select form="userSearchRequest" name="category" class="search-s">
          <option value="name">名前</option>
          <option value="id">社員ID</option>
        </select>
      </div>
      <div class="margin-10">
        <label>並び替え</label><br>
        <select name="updown" form="userSearchRequest" class="search-s">
          <option value="ASC">昇順</option>
          <option value="DESC">降順</option>
        </select>
      </div>
      <div class="margin-10 ">
        <p class="m-0 search-waku"><span class="search_conditions">検索条件の追加　</span></p>
        <div class="search_conditions_inner">
          <div>
            <label>性別</label>
            <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
            <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
          </div>
          <div>
            <label>権限</label><br>
            <select name="role" form="userSearchRequest" class="engineer search-s">
              <option selected disabled>----</option>
              <option value="1">教師(国語)</option>
              <option value="2">教師(数学)</option>
              <option value="3">教師(英語)</option>
              <option value="4" class="">生徒</option>
            </select>
          </div>
          <div class="selected_engineer mag-top10">
            <label>選択科目</label>
            <div class="yoko">
              @foreach($subjects as $subject)
              <div class="yoko">
                <label class="form-check-label">{{ $subject->subject }}</label>
                <div class="check-input">
                  <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" form="userSearchRequest">
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <div class="margin-10">
        <input type="submit" class="post-creat-s" name="search_btn" value="検索" form="userSearchRequest">
      </div>
      <div class="margin-10">
        <input type="reset" class="bk-toumei-waku w80" value="リセット" form="userSearchRequest">
      </div>
    </div>
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
  </div>
</div>
@endsection
