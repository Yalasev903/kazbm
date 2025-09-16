@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
@section('content')
    <main class="articlesPage">
        <div class="container">
            @include('.components.breadcrumbs')
            <div class="titles">{{__($page->title)}}</div>
            <div class="articleItems">
                @include('components.articles.items')
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {

            getPaginations();

            function getPaginations() {
                let items = $(".pogination .pagination_item:not('.active')")
                console.log(items)
                if (items.length > 0) {
                    items.each(function () {
                        $(this).on('click', function (e) {
                            e.preventDefault();
                            let urlParams = $(this).data('href').split('?');
                            var dataType = urlParams[1].split('=')[1];
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '/ajax/pagination/articles',
                                type: "POST",
                                data: {
                                    page: dataType ? dataType : 1
                                },
                                success: function(response){
                                    $(".articleItems").html(response.html)
                                    getPaginations()
                                },
                                error: function(data) {
                                    console.log(data)
                                }
                            });
                        })
                    })
                }
            }
        })
    </script>
@endsection
