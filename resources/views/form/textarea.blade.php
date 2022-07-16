<div class="{{ isset($size) ? $size : "col-12" }}">
    @if(isset($label))
        <label class="text-secondary text-truncate" for="{{ isset($id) ? $id : "idRand" }}">{{ isset($label) ? $label : "Preencha" }}</label>
    @endif
    {{ Form::textarea(isset($id) ? $id : "idRand", isset($value) ? $value : null, array_merge(isset($acoes) ? $acoes : [], ["id" => isset($id) ? $id : "idRand","class" => $class], $attributes)) }}
</div>