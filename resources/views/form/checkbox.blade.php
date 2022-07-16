<div class="{{ isset($size) ? $size : "col-12" }}">
    @if(isset($label))
        <label class="cursor-pointer" for="{{ isset($id) ? $id : "idRand" }}">{{ isset($label) ? $label : "Preencha" }}</label>
    @endif
    {{ Form::checkbox(isset($id) ? $id : "", null, false, ["onchange" => "EnderecoDaObra(this);","id" => isset($id) ? $id : "idRand","class" => $class]) }}
</div>