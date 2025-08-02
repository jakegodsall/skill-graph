@props(['disabled' => false])
<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'fa-icon form-control w-full']) !!}>
    {{$slot}}
</select>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", () => {
        readyIconSelect();
    });
    document.addEventListener("ready-icons", () => {
        readyIconSelect();
    });
    function readyIconSelect(){
        // icon
        $('#{{ $attributes['id'] }}').select2({
            width   : '100%',
            ajax    : {
                url             : 'https://api.fontawesome.com',
                contentType     : 'application/json',
                method          : 'POST',
                data            : function(params){
                    return JSON.stringify({
                        query  : "query { search(version: \"6.4.0\", query:\"" + params.term + "\", first:10) { id, label, styles } }"
                    });
                },
                processResults  : function(data) {
                    let map = $.map(data.data.search, function(obj){
                        let type = 'far';
                        if($.inArray('branches', obj.styles) != -1) type = 'fab';

                        obj.id = type + ' fa-' + obj.id;
                        obj.text = obj.label;
                        return obj;
                    });

                    return {
                        results: map
                    }
                },
                dataType        : 'json',
            },
            placeholder     : "{{ __('Please search for icons') }}",
            minimumInputLength: 3,
            allowClear      : true,
            templateResult  : function(result){
                let html = $('<span><i class="fa-fw ' + result.id + '"></i> ' + result.text + '</span>');
                return html;
            },
            templateSelection   : function(result){
                let html = $('<span><i class="fa-fw ' + result.id + '"></i> ' + result.id + '</span>');
                return html;
            }
        }).change(function (e) {
            @if(isset($attributes['wire:model']))
                @this.set('{{$attributes['wire:model']}}', $(this).val());
            @endif
        });
    }
</script>
