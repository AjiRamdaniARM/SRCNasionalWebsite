<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Perlombaan</th>
            <th>Sekolah</th>
            <th>Alamat</th>
            <th>Peserta</th>
            <th>Kelas</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($participants as $participant)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $participant->race }}</td>
                <td>{{ $participant->community }}</td>
                <td>{{ $participant->maps }}</td>
                <td>{{ $participant->peserta }}</td>
                <td>{{ $participant->kelas_peserta }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
