<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{page_title}</h3>
    </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">{page_title} Management</li>
        </ol>
    </div>
    <div></div>
</div>
<!-- Main content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-sm-2">
                    </div>
                    <small>
                        <table class="table table-hover table-bordered" id="myTable" style="width:100%">   
                            <thead>
                                <tr>
                                    <th class='no-sort'>No</th>
                                    <th>Kode Barang</th> <!-- 1 -->
                                    <th>Nama Barang</th> <!-- 2 -->
                                    <th>Kategori</th> <!-- 4 -->
                                    <th>Jenis</th> <!-- 5 -->                                
                                    <th>Stock</th> <!-- 8 -->                                 
                                    <th class='no-sort' width="100" align="center">
                                        <center>Action</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="show_detail" tabindex="-1" role="dialog" aria-labelledby="advanced_filter_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="advanced_filter_modal">Detil Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><small>Kode Barang</small></label>
                            <input  readonly class="form-control error" type="text" id="kode_barang" name="kode_barang">                                                
                        </div>
                </div>
            </div>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><small>Nama Barang</small></label>
                            <input  readonly class="form-control error" type="text" id="nama_barang" name="nama_barang">                                                
                        </div>
                </div>
            </div>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><small>Kategori</small></label>
                            <input  readonly class="form-control error" type="text" id="kategori_id" name="kategori_id">                                                
                        </div>
                </div>
            </div>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><small>Jenis</small></label>
                            <input  readonly class="form-control error" type="text" id="jenis_id" name="jenis_id">                                                
                        </div>
                </div>
            </div>
      </div>
      <!-- <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><small>Tipe</small></label>
                            <input  readonly class="form-control error" type="text"  id="tipe_id" name="tipe_id">                                                
                        </div>
                </div>
            </div>
      </div> -->
      <!-- <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><small>Merk</small></label>
                            <input  readonly class="form-control error" type="text" id="merk_id" name="merk_id">                                                
                        </div>
                </div>
            </div>
      </div> -->
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><small>Stock Saat Ini</small></label>
                            <input  readonly class="form-control error" type="text"  id="stock" name="stock">                                                
                        </div>
                </div>
            </div>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><small>vendor</small></label>
                            <input  readonly class="form-control error" type="text"  id="vendor_id" name="vendor_id">                                                
                        </div>
                </div>
            </div>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><small>Deskrpsi</small></label>
                            <input  readonly class="form-control error" type="text"  id="deskripsi" name="deskripsi">                                                
                        </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-danger"  id="submit_filter">Filter List</button> -->
      </div>
    </div>
  </div>
</div>

<script src="{base_url}assets/master/script/master_template.js"></script>
<script> 
    var base_url        = '{base_url}';
    var page            = '{page}';
    var parent_page     = '{parent_page}';

    jQuery(document).ready(function() {

        Master.init();
        listDataTable;
    });


    var listDataTable = $("#myTable").DataTable({
	processing: true,
	destroy: true,
	serverSide: true,
	responsive: true,
	autoWidth: false,
	colReorder: true,
	bInfo	: false,
	columnDefs: [
		{ width: "2%", targets: 0 },
		{ className: "text-center", targets: [6] }
	],
	dom: "Brftlp",
	buttons: [
		{
			extend: "colvis",
			text: '<i class="fa fa-list"></i>',
			className: "btn-sm"
        },
        {
			text: '<i class="fa fa-filter"></i>&nbsp;Filter',
			className: "btn-sm bg-red",
			action: function(e, dt, node, config) {
                Master.showModal($('#advanced_filter_modal'));
			}
        },
		{
			extend: "excelHtml5",
			text: '<i class="fa fa-file-excel-o"></i>&nbsp;XLS',
			className: "btn-sm",
			exportOptions: {
				columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
			}
		},
		{
			extend: "csvHtml5",
			text: '<i class="fa fa-file"></i>&nbsp;CSV',
			className: "btn-sm",
			exportOptions: {
				columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
			}
		},
		{
			extend: "pdfHtml5",
			orientation: "landscape",
			pageSize: "LEGAL",
			text: '<i class="fa fa-file-pdf-o"></i>&nbsp;PDF',
			className: "btn-sm",
			exportOptions: {
				columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
			}
		},
		{
			text: '<i class="fa fa-plus"></i>&nbsp;ADD',
			className: "btn-sm bg-red",
			action: function(e, dt, node, config) {
				document.location.href = base_url + "main#"+parent_page+"/form/";
			}
        }
	],
	ajax: {
		url: base_url + parent_page+"/json_list",
		type: "POST"
	},
	bStateSave: true,
	fnStateSave: function(oSettings, oData) {
		localStorage.setItem("offersDataTables", JSON.stringify(oData));
	},
	fnStateLoad: function(oSettings) {
		return JSON.parse(localStorage.getItem("offersDataTables"));
	}
});

var show_detail = function(id){

    $.ajax({
        url: base_url + parent_page +"/modal",
        method:"POST",
        data:{id:id},   
        dataType: "JSON",
        
        success:function(data){
            //data = JSON.parse(res);
            $("#kode_barang").val(data.kode_barang);
            $("#nama_barang").val(data.nama_barang);
            $("#kategori_id").val(data.kategori_id);
            $("#jenis_id").val(data.jenis_id);
            $("#tipe_id").val(data.tipe_id);
            $("#merk_id").val(data.merk_id);
            $("#stock").val(data.stock);
            $("#vendor_id").val(data.vendor_id);
            $("#deskripsi").val(data.deskripsi);
            $('#show_detail').modal('show');
        }
        
    });

    
}

var del = function(id) {
	swal(
		{
			title: "Are you sure?",
			text: "You will not be able to recover this item!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, remove it",
			cancelButtonText: "Cancel",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm) {
        if (isConfirm) {
            // alert(parent_page);
				$.ajax({
					url: base_url + parent_page +"/delete",
					type: "POST",
					dataType: "json",
					data: { id:id },
					success: function(data) {
                       // console.log(data);
						if (data.status == true) {
							swal({
								title: "Deleted!",
								text: "Your item has been deleted.",
								type: "success",
								confirmButtonClass: "btn-success"
							});
							listDataTable.ajax.reload();
						} else {
							swal({
								title: "Error",
								text: data.reason,
								type: "error",
								confirmButtonClass: "btn-danger"
							});
						}
					 }
				});
			} else {
				swal({
					title: "Cancelled",
					text: "Your item is safe !",
					type: "error",
					confirmButtonClass: "btn-danger"
				});
			}
		}
	);
};
</script>

<!-- <script src="{base_url}assets/master/script/admin_tenant.js"></script> -->
