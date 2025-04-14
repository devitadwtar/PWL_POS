<form action="{{ url('/supplier/ajax') }}" method="POST" id="form-tambah">
  @csrf
  <div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
          <label>Kode Supplier</label>
          <input type="text" name="supplier_kode" id="supplier_kode" class="form-control" required>
          <small id="error-supplier_kode" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
          <label>Nama Supplier</label>
          <input type="text" name="supplier_nama" id="supplier_nama" class="form-control" required>
          <small id="error-supplier_nama" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
          <label>Alamat</label>
          <textarea name="alamat" id="alamat" class="form-control" rows="2" required></textarea>
          <small id="error-alamat" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
          <label>No. Telepon</label>
          <input type="text" name="telepon" id="telepon" class="form-control" required>
          <small id="error-telepon" class="error-text form-text text-danger"></small>
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
    $("#form-tambah").validate({
      rules: {
        supplier_kode: { required: true, minlength: 2, maxlength: 10 },
        supplier_nama: { required: true, minlength: 3, maxlength: 100 },
        alamat: { required: true, minlength: 5 },
        telepon: { required: true, minlength: 5, maxlength: 20 }
      },
      submitHandler: function (form) {
        $.ajax({
          url: form.action,
          type: form.method,
          data: $(form).serialize(),
          success: function (response) {
            if (response.status) {
              $('#myModal').modal('hide');
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message
              });
              reloadSupplierTable(); // reload DataTable khusus supplier
            } else {
              $('.error-text').text('');
              $.each(response.msgField, function (prefix, val) {
                $('#error-' + prefix).text(val[0]);
              });
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: response.message
              });
            }
          }
        });
        return false;
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element) {
        $(element).removeClass('is-invalid');
      }
    });
  });
</script>
