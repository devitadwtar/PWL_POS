<form action="{{ url('/level/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Level</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label><br>
                    <a href="{{ asset('template_level.xlsx') }}" class="btn btn-info btn-sm" download>
                        <i class="fa fa-file-excel"></i> Download Template
                    </a>
                </div>
                <div class="form-group">
                    <label>Pilih File Excel</label>
                    <input type="file" name="file_level" id="file_level" class="form-control" required>
                    <small id="error-file_level" class="error-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>

<script>
  $(document).ready(function() {
    $("#form-import").validate({
      rules: {
        file_level: {
          required: true,
          extension: "xlsx"
        }
      },
      submitHandler: function(form) {
        var formData = new FormData(form);

        $.ajax({
          url: form.action,
          type: form.method,
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.status) {
              $('#myModal').modal('hide');
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message
              });
              if (typeof dataLevel !== 'undefined') {
                dataLevel.ajax.reload();
              }
            } else {
              $('.error-text').text('');
              $.each(response.msgField, function(prefix, val) {
                $('#error-' + prefix).text(val[0]);
              });
              Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: response.message
              });
            }
          },
          error: function(xhr, status, error) {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: 'Terjadi kesalahan saat mengunggah file.'
            });
          }
        });

        return false;
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
</script>
