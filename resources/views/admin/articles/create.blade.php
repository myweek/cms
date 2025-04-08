@extends('admin.layouts.app')

@section('title', '创建文章')

@section('styles')
<style>
    .ck-editor__editable_inline {
        min-height: 300px;
    }
    .ck-content img {
        max-width: 100%;
        height: auto;
    }
    .ck-toolbar {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.375rem !important;
    }
    .ck-editor__editable {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.375rem !important;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">创建文章</h1>
            <a href="{{ route('admin.articles.index') }}" class="text-indigo-600 hover:text-indigo-900">
                返回列表
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="content" id="content-field">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">文章标题</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">文章别名</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">用于 URL 的唯一标识，只能包含字母、数字、中划线和下划线</p>
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">所属栏目</label>
                        <select name="category_id" id="category_id" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">请选择栏目</option>
                            @foreach(\App\Models\Column::where('is_active', true)->orderBy('order')->get() as $column)
                                <option value="{{ $column->id }}" {{ old('category_id') == $column->id ? 'selected' : '' }}>
                                    {{ $column->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">封面图片</label>
                        <input type="file" name="cover_image" id="cover_image" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-500">支持 JPG, PNG, GIF 格式图片</p>
                    </div>

                    <div class="col-span-2">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">文章摘要</label>
                        <textarea name="excerpt" id="excerpt" rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">简短的文章介绍，会显示在列表页</p>
                    </div>

                    <div class="col-span-2">
                        <label for="editor-content" class="block text-sm font-medium text-gray-700 mb-1">文章内容</label>
                        <div id="editor-container">
                            <div id="toolbar-container" class="border border-gray-300 rounded-md p-2 mb-2"></div>
                            <div id="editor-content" class="min-h-[300px] border border-gray-300 rounded-md p-4"></div>
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">文章状态</label>
                        <select name="status" id="status" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>草稿</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>发布</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>待审核</option>
                        </select>
                    </div>

                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">发布时间</label>
                        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">留空表示立即发布</p>
                    </div>

                    <div class="col-span-2 flex space-x-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_recommended" id="is_recommended" value="1" {{ old('is_recommended') ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_recommended" class="ml-2 block text-sm text-gray-900">推荐文章</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_top" id="is_top" value="1" {{ old('is_top') ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_top" class="ml-2 block text-sm text-gray-900">置顶文章</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-5">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        保存文章
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- 引入CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 获取编辑器容器和隐藏的内容字段
        const editorContent = document.getElementById('editor-content');
        const contentField = document.getElementById('content-field');

        // 自定义图片上传适配器
        class UploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return new Promise((resolve, reject) => {
                    this.loader.file.then(file => {
                        const formData = new FormData();
                        formData.append('upload', file);

                        fetch('/admin/upload-image', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.uploaded) {
                                resolve({
                                    default: response.url,
                                    fileName: response.fileName
                                });
                            } else {
                                reject(response.error?.message || '上传失败');
                            }
                        })
                        .catch(error => {
                            console.error('上传失败:', error);
                            reject('图片上传失败');
                        });
                    });
                });
            }

            abort() {
                // 中止上传
            }
        }

        // 初始化CKEditor
        ClassicEditor
            .create(editorContent, {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'link', 'blockQuote', 'insertTable', 'uploadImage', '|',
                        'undo', 'redo'
                    ]
                },
                language: 'zh-cn',
                image: {
                    toolbar: [
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side',
                        '|',
                        'toggleImageCaption',
                        'imageTextAlternative'
                    ],
                    upload: {
                        types: ['jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff']
                    }
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                }
            })
            .then(editor => {
                // 注册自定义上传适配器
                editor.plugins.get('FileRepository').createUploadAdapter = loader => {
                    return new UploadAdapter(loader);
                };

                // 监听编辑器内容变化
                editor.model.document.on('change:data', () => {
                    contentField.value = editor.getData();
                });

                // 监听粘贴事件
                editor.editing.view.document.on('clipboardInput', async (evt, data) => {
                    const clipboardData = data.dataTransfer;
                    const items = Array.from(clipboardData.items || []);
                    const htmlContent = clipboardData.getData('text/html');
                    
                    // 处理直接粘贴的图片文件
                    for (const item of items) {
                        if (item.type.startsWith('image/')) {
                            evt.stop();
                            const file = item.getAsFile();
                            if (file) {
                                const formData = new FormData();
                                formData.append('upload', file);
                                
                                try {
                                    const response = await fetch('/admin/upload-image', {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        }
                                    });
                                    
                                    const result = await response.json();
                                    
                                    if (result.uploaded) {
                                        editor.execute('insertImage', {
                                            source: result.url
                                        });
                                    } else {
                                        console.error('图片上传失败:', result.error?.message);
                                        alert('图片上传失败: ' + (result.error?.message || '未知错误'));
                                    }
                                } catch (error) {
                                    console.error('图片上传出错:', error);
                                    alert('图片上传出错: ' + error.message);
                                }
                            }
                            return;
                        }
                    }
                    
                    // 处理HTML内容中的图片
                    if (htmlContent) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = htmlContent;
                        const images = tempDiv.getElementsByTagName('img');
                        
                        if (images.length > 0) {
                            evt.stop();
                            let processedImages = 0;
                            
                            for (const img of images) {
                                try {
                                    const response = await fetch('/admin/proxy-image', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({ url: img.src })
                                    });
                                    
                                    const result = await response.json();
                                    
                                    if (result.uploaded) {
                                        img.src = result.url;
                                        processedImages++;
                                    }
                                } catch (error) {
                                    console.error('图片处理失败:', error);
                                }
                            }
                            
                            // 如果所有图片都处理完成，插入处理后的HTML内容
                            if (processedImages === images.length) {
                                const viewFragment = editor.data.processor.toView(tempDiv.innerHTML);
                                const modelFragment = editor.data.toModel(viewFragment);
                                editor.model.insertContent(modelFragment);
                            } else {
                                // 如果有图片处理失败，仍然插入文本内容
                                const textContent = tempDiv.innerText.trim();
                                if (textContent) {
                                    const viewFragment = editor.data.processor.toView(textContent);
                                    const modelFragment = editor.data.toModel(viewFragment);
                                    editor.model.insertContent(modelFragment);
                                }
                            }
                            return;
                        }
                    }
                    
                    // 如果没有图片，让编辑器处理默认的粘贴行为
                });

                // 表单提交前确保内容已更新
                document.querySelector('form').addEventListener('submit', function(e) {
                    const content = editor.getData();
                    contentField.value = content;
                    
                    if (!content.trim()) {
                        e.preventDefault();
                        alert('文章内容不能为空！');
                        return false;
                    }
                });

                // 如果有旧的内容，设置到编辑器中
                if (contentField.value) {
                    editor.setData(contentField.value);
                }
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
            });
    });

    // 根据标题自动生成别名
    document.getElementById('title').addEventListener('input', function() {
        const title = this.value;
        const slug = title
            .toLowerCase()
            .replace(/[\s\W-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        
        document.getElementById('slug').value = slug;
    });
</script>
@endsection 