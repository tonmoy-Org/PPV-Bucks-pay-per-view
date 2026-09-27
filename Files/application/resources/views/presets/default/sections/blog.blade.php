@php
    $blog = getContent('blog.content', true);
    $blogs = getContent('blog.element', false, 2);
@endphp
<!-- ==================== Blog Start ==================== -->
<section class="blog py-80">
    <div class="container">
        <div class="title">
            <h4>{{__(@$blog->data_values->heading)}}</h4>
            <p>{{__(@$blog->data_values->sub_heading)}}</p>
        </div>
        @include($activeTemplate.'components.blog')
    </div>
</section>
<!-- ==================== Blog End ==================== -->
