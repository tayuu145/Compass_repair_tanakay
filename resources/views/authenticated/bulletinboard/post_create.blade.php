@extends('layouts.sidebar')

@section('content')

<div class="post_create_container d-flex">
  <div class="post_create_area border w-50 m-5 p-5">
    <div class="">
      <p class="mb-0">カテゴリー</p>
      <select class="w-100" form="postCreate" name="post_category_id">
        @foreach($main_categories as $main_category)

        <optgroup label="{{ $main_category->main_category }}" class="gray"></optgroup>


        @foreach($sub_categories as $sub_category)
        <!-- サブカテゴリー表示 -->
        @if($main_category->id==$sub_category->main_category_id)
        <option label="{{ $sub_category->sub_category }}" value="{{$sub_category->id}}"></option>
        @endif
        @endforeach


        @endforeach
        </optgroup>
      </select>
    </div>
    <div class="mt-3">
      @if($errors->first('post_title'))
      <span class="error_message">{{ $errors->first('post_title') }}</span>
      @endif
      <p class="mb-0">タイトル</p>
      <input type="text" class="w-100" form="postCreate" name="post_title" value="{{ old('post_title') }}">
    </div>
    <div class="mt-3">
      @if($errors->first('post_body'))
      <span class="error_message">{{ $errors->first('post_body') }}</span>
      @endif
      <p class="mb-0">投稿内容</p>
      <textarea class="w-100" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
    </div>
    <div class="mt-3 text-right">
      <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
    </div>
    <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
  </div>
  @can('admin')
  <div class="w-25 ml-auto mr-auto">
    <div class="category_area mt-5 p-5">
      <div class="">
        <p class="m-0">メインカテゴリー</p>
        <input type="text" class="w-100" name="main_category_name" form="mainCategoryRequest">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="mainCategoryRequest">
      </div>
      <!-- サブカテゴリー追加 -->
      <div class="">
        <p class="m-0">サブカテゴリー</p>
        <select class="w-100" name="main_category_id" form="subCategoryRequest">
          @foreach($main_categories as $main_category)
          <option label="{{ $main_category->main_category }}" value="{{ $main_category->id }}"></option>

          @endforeach
          </optgroup>
        </select>
        <input type="text" class="w-100" name="sub_category" form="subCategoryRequest">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="subCategoryRequest">
        @if ($errors->has('main_category_id'))
        <div class="alert-danger">
          <ul>
            <!-- ↓送られたerror名 -->
            @foreach ($errors->get('main_category_id') as $validator)
            <li>{{ $validator }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @if ($errors->has('sub_category'))
        <div class="alert-danger">
          <ul>
            <!-- ↓送られたerror名 -->
            @foreach ($errors->get('sub_category') as $validator)
            <li>{{ $validator }}</li>
            @endforeach
          </ul>
        </div>
        @endif

      </div>
      <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">{{ csrf_field() }}</form>
      <form action="{{ route('sub.category.create') }}" method="post" id="subCategoryRequest">{{ csrf_field() }}</form>
    </div>
  </div>
  @endcan
</div>
@endsection
