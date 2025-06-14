<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tabla 3x3 con Imágenes</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            width: 33%;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #ccc;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<table class="w-full">
    @for ($row = 0; $row < 3; $row++)
        <tr>
            @for ($col = 0; $col < 3; $col++)
                <td>
                    <img src="{{ $images[$row][$col] }}" alt="Imagen {{ $row*3 + $col + 1 }}">
                </td>
            @endfor
        </tr>
    @endfor
</table>

</body>
</html>
