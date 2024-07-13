    @extends('layouts.admin')
    @section('title')
    Thêm bài viết
@endsection
    @section('content')
        <div id="content" class="container-fluid">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Thêm bài viết
                </div>
                <div class="card-body">
                    <form action="{{ route("post.store") }}" method='POST' enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="fw-550">Tiêu đề</label>
                            <input class="form-control" type="text" name="post_title" id="name"
                                value="{{ Old('post_title') }}">
                            @error('post_title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="slug" class="fw-550">Slug (Friendly Url)</label>
                            <input class="form-control" type="text" name="slug" id="slug" readonly
                                value="{{ Old('slug') }}">
                            
                        </div>

                        <div class="form-group">
                            <label for="post-desciption" class="fw-550">Mô tả</label>
                            <textarea class="form-control" name="post_description" id="post-desciption">{{ Old('post_description') }}</textarea>
                            @error('post_description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="post-content" class="fw-550">Nội dung bài viết</label>
                            <textarea class="form-control" name="post_content" id="post-content">{{ Old('post_content') }}</textarea>
                            @error('post_content')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for='post-thumb' class="fw-550">Hình ảnh</label> <BR>
                            <div id="uploadFile">
                                <input type="file" name="post_image" id="post-thumb" class="form-control-file upload_file"
                                    onchange="upload_image(this)">
                                {{-- <img src={{ url('public/uploads/img-product1.png') }} id="image_upload_file" class="mt-3"> --}}
                            </div>

                            @error('post_image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- Danh mục bài viết --}}
                        <div class="form-group">
                            <label for="post_cat" class="fw-550">Danh mục</label>

                            @if (!empty($list_category))
                                <select name="post_cat[]" id="post_cat" class="form-control" multiple>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($list_category as $post_cat)
                    
                                        <option value="{{ $post_cat->id }}">
                                            {{ str_repeat('-', $post_cat->level) . ' ' . $post_cat->post_cat_name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="empty-task">Không tồn tại danh mục nào</p>
                            @endif
                            @error('post_cat')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- Trạng thái --}}
                        <div class="form-group">
                            <label for="">Trạng thái</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="post_status" id="exampleRadios1"  value="Hoạt động">
                                <label class="form-check-label" for="exampleRadios1">
                                    Hoạt động
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="post_status" id="exampleRadios2" value="Chưa phê duyệt">
                                <label class="form-check-label" for="exampleRadios2">
                                    Chưa phê duyệt
                                </label>
                            </div>
                            @error('post_status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <input type="submit" name="btn_add" class="btn btn-primary" value="Thêm mới">
                    </form>
                </div>
            </div>
        </div>
    @endsection