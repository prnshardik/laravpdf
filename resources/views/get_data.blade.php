<html>
    <head>

    </head>
    <body>
        @if($data)
            <table border="1">
                <thead>
                    <th>id</th>
                    <th>Text</th>
                </thead>
                <tbody>
                    @foreach($data AS $row)
                        <tr>
                            <td>
                                {{ $row->id }}
                            </td>
                            <td>
                                {{$row->content}} 
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        @endif
    </body>
</html>