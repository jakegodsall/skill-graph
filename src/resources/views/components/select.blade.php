
<?php
// If we are manually setting disabled to false, dont include it.
if(isset($attributes['disabled']) && ((string)$attributes['disabled'] == "false" ||  $attributes['disabled'] == false)){
    unset($attributes['disabled']);
}
?>

<select {!! $attributes->merge(['class' => 'form-control w-full']) !!}>
    {{$slot}}
</select>
