<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Community</th>
            <th>Maps</th>
            <th>Sesi</th>
            <th>Waktu Awal</th>
            <th>Waktu Akhir</th>
            <th>Duration</th>
            <th>Race</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($participants as $participant)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $participant->peserta }}</td>
                <td>{{ $participant->community }}</td>
                <td>{{ $participant->maps }}</td>
                <td>{{ $participant->sesi }}</td>
                <td>{{ $participant->waktu_awal }}</td>
                <td>{{ $participant->waktu_akhir }}</td>
                <td>{{ $participant->duration }}</td>
                <td>{{ $participant->race }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
