<form action="{{ url('/level/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Level</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Kode Level</label>
            <input type="text" name="level_kode" id="level_kode" class="form-control" required>
            <small id="error-level_kode" class="error-text form-text text-danger"></small>
          </div>
  
          <div class="form-group">
            <label>Nama Level</label>
            <input type="text" name="level_nama" id="level_nama" class="form-control" required>
            <small id="error-level_nama" class="error-text form-text text-danger"></small>
          </div>
        </div>
  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </form>
  <script>
    $(document).ready(function () {
      // Validasi form dengan jQuery validate
      $("#form-tambah").validate({
        rules: {
          level_kode: { required: true, minlength: 3, maxlength: 10 },
          level_nama: { required: true, minlength: 3, maxlength: 100 }
        },
        submitHandler: function (form) {
          // Menjalankan AJAX saat form disubmit
          $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),  // Mengambil semua data form
            success: function (response) {
              // Jika sukses
              if (response.status) {
                $('#myModal').modal('hide');  // Menutup modal
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: response.message
                });
                dataLevel.ajax.reload();  // Reload DataTable untuk menampilkan data terbaru
              } else {
                // Jika gagal
                $('.error-text').text('');  // Hapus pesan error sebelumnya
                $.each(response.msgField, function (prefix, val) {
                  $('#error-' + prefix).text(val[0]);  // Tampilkan pesan error untuk field yang bermasalah
                });
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: response.message
                });
              }
            },
            error: function () {
              // Penanganan jika terjadi error saat request AJAX
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat mengirim data.'
              });
            }
          });
          return false;  // Mencegah form dari submit tradisional
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');  // Menambahkan kelas untuk feedback error
          element.closest('.form-group').append(error);  // Menempatkan pesan error setelah input field
        },
        highlight: function (element) {
          $(element).addClass('is-invalid');  // Menambahkan kelas is-invalid pada input yang tidak valid
        },
        unhighlight: function (element) {
          $(element).removeClass('is-invalid');  // Menghapus kelas is-invalid pada input yang valid
        }
      });
    });
  </script>
    