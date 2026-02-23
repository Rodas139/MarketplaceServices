@if (!empty($row->filepath))
    {{-- {{ HTML::image($row->getThumb(), null, array('width' => '40px', 'class' => 'image-preview', 'data-img' => $row->getCroppa(200))) }} --}}
@else
    {{ HTML::image(asset('assets/img/default/default_thumb.png'), null, ['width' => '40px','class' => 'image-preview','data-img' => asset('assets/img/default/avatar.png')]) }}
@endif
