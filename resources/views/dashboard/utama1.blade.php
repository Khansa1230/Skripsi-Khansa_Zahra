<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa Teknik Informatika</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Data Mahasiswa Teknik Informatika</h1>

        <div class="mb-4">
            <form action="{{ route('utama') }}" method="GET" id="filterForm">
                <!-- <div class="form-group">
                    <label for="year">Pilih Tahun Angkatan:</label>
                    <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Pilih Tahun --</option>
                        @foreach ($years as $yearOption)
                            <option value="{{ $yearOption->year }}" {{ $year == $yearOption->year ? 'selected' : '' }}>
                                {{ $yearOption->year }}
                            </option>
                        @endforeach
                    </select>
                </div> -->

                <div class="form-group">
                    <label for="status">Pilih Status:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">-- Pilih Status --</option>
                        @foreach ($allStatus as $status)
                            <option value="{{ $status->status }}" {{ request('status') == $status->status ? 'selected' : '' }}>
                                {{ $status->status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jurusan">Pilih Jurusan:</label>
                    <select name="jurusan" id="jurusan" class="form-control">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach ($allJurusan as $jurusan)
                            <option value="{{ $jurusan->jurusan }}" {{ request('jurusan') == $jurusan->jurusan ? 'selected' : '' }}>
                                {{ $jurusan->jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="propinsi">Pilih Propinsi:</label>
                    <select name="propinsi" id="propinsi" class="form-control">
                        <option value="">-- Pilih Propinsi --</option>
                        @foreach ($allPropinsi as $propinsi)
                            <option value="{{ $propinsi->propinsi }}" {{ request('propinsi') == $propinsi->propinsi ? 'selected' : '' }}>
                                {{ $propinsi->propinsi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function() {
    function updatePropinsi() {
        var tahun = $('#year').val();
        var status = $('#status').val();
        var jurusan = $('#jurusan').val();

        $.ajax({
            url: '{{ route('utama') }}',
            type: 'GET',
            data: { year: tahun, status: status, jurusan: jurusan },
            success: function(data) {
                console.log('Data dari server:', data); // Cek respons dari server
                $('#propinsi').empty().append('<option value="">-- Pilih Propinsi --</option>');
                if (data.propinsi) {
                    $.each(data.propinsi, function(index, value) {
                        $('#propinsi').append('<option value="' + value.propinsi + '">' + value.propinsi + '</option>');
                    });
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr);
            }
        });
    }

    $('#year, #status, #jurusan').change(updatePropinsi);
});


    </script>
</body>
</html>
