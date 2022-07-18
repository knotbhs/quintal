<div class="form-group {{ isset($class) ? $class : '' }}">

    @if (isset($label) && !empty($label))
        <label class=" col-form-label" for="{{ isset($id) ? $id : '' }}">{{ $label }}
            {!! isset($plus) && !empty($plus)
                ? '<a data-toggle="tooltip" data-placement="top" title="' .
                    (isset($titleTooltipo) ? $titleTooltipo : '') .
                    '" href="' .
                    $plus .
                    '" class="fa fa-plus-circle cursor-pointer"></a>'
                : '' !!}</label>
    @endif
    <select onchange="{{ isset($onchange) ? $onchange : '' }}" id="{{ isset($id) ? $id : '' }}"
        name="{{ isset($id) ? $id : '' }}" class="form-control">
        @if (count($options) > 0)
            <option selected="true" value="">Selecione...</option>
            <option value=""></option>
        @endif
        @foreach ($options as $key => $opt)
            @if (!isset($compararCom) || $compararCom == 'id')
                @if ($value == $key)
                    <option selected="true" value="{{ $key }}">{{ $opt }}</option>
                @else
                    <option value="{{ $key }}">{{ $opt }}</option>
                @endif
            @else
                @if ($value == $opt)
                    <option selected="true" value="{{ $key }}">{{ $opt }}</option>
                @else
                    <option value="{{ $key }}">{{ $opt }}</option>
                @endif
            @endif
        @endforeach
    </select>
</div>
