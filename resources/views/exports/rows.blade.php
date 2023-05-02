<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
<table>
    <thead>
        <tr>
            <th style="font-weight:bold;" colspan="13">ԱՊՐԱՆՔՆԵՐԻ</th>
        </tr>
        <tr>
            <th style="font-weight:bold;">Չափաբաժնի<br/>համարը</th>
            <th style="font-weight:bold;">Գնումների պլանով<br/>նախատեսված միջանցիկ<br/>ծածկագիրը` ըստ ԳՄԱ<br/>դասակարգման (CPV)</th>
            <th style="font-weight:bold;">Անվանումը</th>
            <th style="font-weight:bold; color:red">Անվանումը<br/>(ռուսերեն)</th>
            <th style="font-weight:bold;">Տեխնիկական<br/>բնութագիրը</th>
            <th style="font-weight:bold; color:red">Տեխնիկական<br/>բնութագիրը (ռուսերեն)</th>
            <th style="font-weight:bold;">Չափման<br/>միավորը</th>
            <th style="font-weight:bold; color:red">Չափման միավորը<br/>(ռուսերեն)</th>
            <th style="font-weight:bold;">Ընդհանուր<br/>քանակը</th>
            <th style="font-weight:bold;">Միավոր գին<br/>(ՀՀ դրամ)</th>
            <th style="font-weight:bold;">Արժեք<br/>(ՀՀ դրամ)</th>
            <th style="font-weight:bold;">ԱԱՀ<br/>(ՀՀ դրամ)</th>
            <th style="font-weight:bold;">Ընդհանուր գին<br/>(ՀՀ դրամ)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
            <tr>
                <td>{{$row->lotNumber}}</td>
                <td></td>
                <td>{{$row->cpvNameArm}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$row->unit}}</td>
                <td></td>
                <td>{{$row->count}}</td>
                <td>{{$row->unit_amount}}</td>
                <td>{{$row->amount}}</td>
                <td>{{$row->vat}}</td>
                <td>{{$row->total}}</td>
            </tr>
        @endforeach
    </tbody>
</table>