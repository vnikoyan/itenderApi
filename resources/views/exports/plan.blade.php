<table>
    <thead>
        <tr>
            <th colspan="7">Պատվիրատուն՝ «{{$plan->organisation->name}}» {{$plan->organisation->company_type}}</th>
        </tr>
        <tr>
            <th colspan="3">Անվանումը</th>
            <th colspan="4">{{$plan->name}}</th>
        </tr>
        <tr>
            <th colspan="2">Գնման առարկայի</th>
            <th rowspan="2">Գնման<br/>ձեւը</th>
            <th rowspan="2">Չափի<br/>միավորը</th>
            <th rowspan="2">Միավորի<br/>գինը</th>
            <th rowspan="2">Քանակը</th>
            <th rowspan="2">Գումարը<br/>(հազ.դրամ)</th>
        </tr>
        <tr>
            <th>միջանցիկ<br/>ծածկագիրը`<br/>(ըստ ԳՄԱ դասակարգման)</th>
            <th>Անվանումը</th>
        </tr>
    </thead>
    {{$total = 0}}
    <tbody>
        <tr>
            <td></td>
            <td style="font-weight:bold;">Ապրանքներ</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($plan->planRows as $row)
            @if (+$row->cpv->type === 1)
                <tr>
                    <td>{{ $row->cpv->code }} {{ $row->cpv_drop ? " / $row->cpv_drop" : '' }}</td>
                    <td>{{ $row->cpv->name }}</td>
                    <td>{{ $purchase_types[$row->details[0]->type - 1] }}</td>
                    <td>{{ $row->unit }}</td>
                    <td>{{ $row->details[0]->unit_amount }}</td>
                    <td>{{ $row->details[0]->count }}</td>
                    <td>{{ $row->details[0]->count * $row->details[0]->unit_amount }}</td>
                </tr>
                {{$total += $row->details[0]->count * $row->details[0]->unit_amount}}
            @endif
        @endforeach
        <tr>
            <td></td>
            <td style="font-weight:bold;">Աշխատանքներ</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($plan->planRows as $row)
            @if (+$row->cpv->type === 3)
                <tr>
                    <td>{{ $row->cpv->code }} {{ $row->cpv_drop ? " / $row->cpv_drop" : '' }}</td>
                    <td>{{ $row->cpv->name }}</td>
                    <td>{{ $purchase_types[$row->details[0]->type - 1] }}</td>
                    <td>{{ $row->unit }}</td>
                    <td>{{ $row->details[0]->unit_amount }}</td>
                    <td>{{ $row->details[0]->count }}</td>
                    <td>{{ $row->details[0]->count * $row->details[0]->unit_amount }}</td>
                </tr>
                {{$total += $row->details[0]->count * $row->details[0]->unit_amount}}
            @endif
        @endforeach
        <tr>
            <td></td>
            <td style="font-weight:bold;">Ծառայություններ</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($plan->planRows as $row)
            @if (+$row->cpv->type === 2)
                <tr>
                    <td>{{ $row->cpv->code }} {{ $row->cpv_drop ? " / $row->cpv_drop" : '' }}</td>
                    <td>{{ $row->cpv->name }}</td>
                    <td>{{ $purchase_types[$row->details[0]->type - 1] }}</td>
                    <td>{{ $row->unit }}</td>
                    <td>{{ $row->details[0]->unit_amount }}</td>
                    <td>{{ $row->details[0]->count }}</td>
                    <td>{{ $row->details[0]->count * $row->details[0]->unit_amount }}</td>
                </tr>
                {{$total += $row->details[0]->count * $row->details[0]->unit_amount}}
            @endif
        @endforeach
        <tr>
            <td></td>
            <td style="font-weight:bold;">Ընդամենը</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$total}}</td>
        </tr>
    </tbody>
</table>